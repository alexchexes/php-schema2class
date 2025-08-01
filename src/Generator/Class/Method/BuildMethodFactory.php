<?php

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\ClassGenerator;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlock\Tag\ThrowsTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class BuildMethodFactory
{
    public const METHOD_NAME = 'buildFromInput';
    public const INPUT_ARG_NAME = 'input';
    public const VALIDATE_ARG_NAME = 'validate';
    public const DEFAULTS_ARG_NAME = 'materializeDefaults';

    public function __construct(
        private GeneratorRequest $request,
        private PropertyCollection $schemaProperties,
        private array $defaults = [],
        private bool $hasOptionalNullable = false,
    ) {}

    public function generate(): MethodGenerator
    {
        $parameterGenerators = $this->buildParams();
        $docBlockGenerators = $this->buildDocBlock();
        $body = $this->generateBody();

        $methodGenerator = new MethodGenerator(
            self::METHOD_NAME,
            $parameterGenerators,
            MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,
            $body,
            $docBlockGenerators
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $methodGenerator->setReturnType(
                $this->request->getTargetNamespace() . '\\' . $this->request->getTargetClass()
            );
        }

        return $methodGenerator;
    }

    /** 
     * @return ParameterGenerator[]
     */
    private function buildParams(): array
    {
        $paramType = null;
        if ($this->request->isAtLeastPHP('8.0')) {
            $paramType = 'array|object';
        }

        $parameterGenerators = [
            new ParameterGenerator(self::INPUT_ARG_NAME, $paramType),
            new ParameterGenerator(self::VALIDATE_ARG_NAME, 'bool', true),
        ];

        if ($this->defaults) {
            $parameterGenerators[] = new ParameterGenerator(self::DEFAULTS_ARG_NAME, 'bool', false);
        }

        return $parameterGenerators;
    }

    private function buildDocBlock(): DocBlockGenerator
    {
        $docBlockParams = [
            new ParamTag(self::INPUT_ARG_NAME, ['array|object'], 'Input data'),
            new ParamTag(self::VALIDATE_ARG_NAME, ['bool'], 'Set this to false to skip validation; use at own risk'),
        ];

        if ($this->defaults) {
            $docBlockParams[] = new ParamTag(self::DEFAULTS_ARG_NAME, ['bool'], 'Apply defaults defined in schema when missing');
        }

        $docBlockParams[] = new ReturnTag([$this->request->getTargetClass()], 'Created instance');
        $docBlockParams[] = new ThrowsTag('\\InvalidArgumentException');

        $docBlock = new DocBlockGenerator(
            'Builds a new instance from an input array',
            null,
            $docBlockParams,
        );
        $docBlock->setWordWrap(false);

        return $docBlock;
    }

    private function ensureUniqueName(string $name): string
    {
        $newName = $name;
        if ($this->schemaProperties->hasPropertyWithName($newName)) {
            $newName = '_' . $name;
            $i = 2;
            while ($this->schemaProperties->hasPropertyWithName($newName)) {
                $newName = '_' . $name . $i;
                $i++;
            }
        }

        return $newName;
    }

    private function generateBody(): string
    {
        $objVarName = $this->ensureUniqueName('obj');

        $optionalProperties = $this->schemaProperties->filter(PropertyCollectionFilterFactory::optional());
        $assignments = [];
        foreach ($optionalProperties as $optionalProperty) {
            $name = $optionalProperty->name();
            $assignments[] = "\${$objVarName}->{$name} = \${$name};";
        }

        $inputArgAlias = $this->ensureUniqueName(self::INPUT_ARG_NAME);
        $validateArgAlias = $this->ensureUniqueName(self::VALIDATE_ARG_NAME);
        $materializeArgAlias = $this->ensureUniqueName(self::DEFAULTS_ARG_NAME);

        $this->request->setCurrValidateArgAlias($validateArgAlias);
        $this->request->setCurrMaterializeArgAlias($this->defaults ? $materializeArgAlias : null);

        $requiredProperties = $this->schemaProperties->filter(PropertyCollectionFilterFactory::required());
        $constructorParams = [];
        foreach ($requiredProperties as $requiredProperty) {
            $constructorParams[] = '$' . $requiredProperty->name();
        }

        $aliasesLines = '';

        // let's accept https://wiki.php.net/rfc/arbitrary_string_interpolation so we don't do this:
        $INPUT_ARG_NAME = self::INPUT_ARG_NAME;
        $VALIDATE_ARG_NAME = self::VALIDATE_ARG_NAME;
        $DEFAULTS_ARG_NAME = self::DEFAULTS_ARG_NAME;

        if (self::INPUT_ARG_NAME !== $inputArgAlias) {
            $aliasesLines .= "\${$inputArgAlias} = \${$INPUT_ARG_NAME};\nunset(\${$INPUT_ARG_NAME});\n";
        }
        if (self::VALIDATE_ARG_NAME !== $validateArgAlias) {
            $aliasesLines .= "\${$validateArgAlias} = \${$VALIDATE_ARG_NAME};\nunset(\${$VALIDATE_ARG_NAME});\n";
        }
        if (self::DEFAULTS_ARG_NAME !== $materializeArgAlias) {
            $aliasesLines .= "\${$materializeArgAlias} = \${$DEFAULTS_ARG_NAME};\nunset(\${$DEFAULTS_ARG_NAME});\n";
        }
        if ($aliasesLines) {
            $aliasesLines .= "\n";
        }

        $inputGuard = '';
        if (!$this->request->isAtLeastPHP('8.0')) {
            $inputGuard =
                "if (!is_array(\$$inputArgAlias) && !is_object(\$$inputArgAlias)) {\n" .
                "    throw new \\InvalidArgumentException(\n" .
                "        'Input to buildFromInput must be array or object, got ' . gettype(\$$inputArgAlias)\n" .
                "    );\n" .
                "}\n\n";
        }

        $materializeLine = '';

        $arrayToObjectExpr = $this->request->getOptions()->getArrayToObjectExpr();

        if ($this->defaults) {
            $convertInputLine =
                "\$$inputArgAlias = is_array(\$$inputArgAlias)\n" .
                "    ? {$arrayToObjectExpr}(\$$inputArgAlias)\n" .
                "    : (\$$materializeArgAlias ? clone \$$inputArgAlias : \$$inputArgAlias);\n\n";

            $materializeLine =
                "if (\$$materializeArgAlias) {\n" .
                "    foreach (self::\$".PropertyNames::DEFAULTS_PROP." as \$__k => \$__v) {\n" .
                "        if (!property_exists(\$$inputArgAlias, (string) \$__k)) {\n" .
                "           \${$inputArgAlias}->{\$__k} = (\$__v['type'] ?? null) === 'object'\n" .
                "               ? {$arrayToObjectExpr}(\$__v['default'])\n" .
                "               : \$__v['default'];\n" .
                "        }\n" .
                "    }\n" .
                "}\n\n";
        } else {
            $convertInputLine =
                "\$$inputArgAlias = is_array(\$$inputArgAlias) ? {$arrayToObjectExpr}(\$$inputArgAlias) : \$$inputArgAlias;\n";
        }

        // local var uses one more underscore
        $optionalsVarName = $this->ensureUniqueName('_'.PropertyNames::OPTIONALS_PROP);

        $body =
            $aliasesLines .
            $inputGuard .
            $convertInputLine .
            $materializeLine .
            "if (\${$validateArgAlias}) {\n" .
            "    static::validateInput(\$$inputArgAlias);\n" .
            "}\n\n" .
            ($this->hasOptionalNullable ? "\${$optionalsVarName} = [];\n" : '') .
            // create variables for each property with conversion from JSON value to PHP type
            $this->schemaProperties->generateInputToTypeConversionCode($inputArgAlias, $optionalsVarName) . "\n\n" .
            // create an instance of the current class
            "\${$objVarName} = new self(" . join(", ", $constructorParams) . ");\n" .
            // assign variables to that instance properties
            join("\n", $assignments) . "\n" .
            // assign the providedOptionals if needed
            ($this->hasOptionalNullable ? "\${$objVarName}->".PropertyNames::OPTIONALS_PROP." = \${$optionalsVarName};\n" : '') .
            // return the instance
            "return \${$objVarName};";

        return $body;
    }
}
