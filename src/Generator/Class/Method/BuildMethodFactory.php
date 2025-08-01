<?php

namespace Helmich\Schema2Class\Generator\Class\Method;

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

    public const OBJ_VAR_NAME = 'obj';

    public function __construct(
        private GeneratorRequest $request,
        private PropertyCollection $schemaProperties,
        private array $defaults = [],
        private bool $hasOptionalNullable = false,
    ) {
    }

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
        $objVarName = $this->ensureUniqueName(self::OBJ_VAR_NAME);
        $providedOptionalsVarName = $this->ensureUniqueName('_'.PropertyNames::OPTIONALS_PROP);

        $inputArgAlias = $this->ensureUniqueName(self::INPUT_ARG_NAME);
        $validateArgAlias = $this->ensureUniqueName(self::VALIDATE_ARG_NAME);
        $materializeArgAlias = $this->ensureUniqueName(self::DEFAULTS_ARG_NAME);

        // TODO: find another way to propagate aliases names instead of mutating GeneratorRequest
        $this->request->setCurrValidateArgAlias($validateArgAlias);
        $this->request->setCurrMaterializeArgAlias($this->defaults ? $materializeArgAlias : null);

        $arrayToObjectExpr = $this->request->getOptions()->getArrayToObjectExpr();

        $bodyParts = [
            // assign arguments to alias vars if there are properties with same names as args
            $this->bodyVarAliases($inputArgAlias, $validateArgAlias, $materializeArgAlias),
            // in target PHP<8 we can't type input as `array|object` so we add a guard
            $this->bodyInputGuard($inputArgAlias),
            // convert input to object if needed, optionally cloning it when "defaults" are present
            $this->bodyInputToObjectConversion($inputArgAlias, $materializeArgAlias, $arrayToObjectExpr),
            // if schema has "defaults", generate "materializeDefaults" block
            $this->bodyMaterializeDefaultsBlock($inputArgAlias, $materializeArgAlias, $arrayToObjectExpr),
            // valide input if argument allows
            $this->bodyInputValidation($inputArgAlias, $validateArgAlias),
            // create "_providedOptionals" variable to keep track on assigned optional nullables
            $this->bodyCreateProvidedOptionalsVar($providedOptionalsVarName),
            // create variables for each property with conversion from JSON value to PHP type
            $this->bodySchemaPropertyVars($inputArgAlias, $providedOptionalsVarName),
            // create an instance of the current class
            $this->bodyCreateNewInstance($objVarName),
            // assign variables to that instance properties
            $this->bodyAssignOptionalsToInstance($objVarName),
            // assign the `_providedOptionals` if needed
            $this->bodyAssignProvidedOptionalsProperty($objVarName, $providedOptionalsVarName),
            // return the instance
            "return \${$objVarName};",
        ];

        $body = implode('', $bodyParts);
        return $body;
    }

    private function bodyVarAliases(string $inputArgAlias, string $validateArgAlias, string $materializeArgAlias): string
    {
        $aliases = '';

        // let's accept https://wiki.php.net/rfc/arbitrary_string_interpolation so we don't do this:
        $INPUT_ARG_NAME = self::INPUT_ARG_NAME;
        $VALIDATE_ARG_NAME = self::VALIDATE_ARG_NAME;
        $DEFAULTS_ARG_NAME = self::DEFAULTS_ARG_NAME;

        if (self::INPUT_ARG_NAME !== $inputArgAlias) {
            $aliases .= "\${$inputArgAlias} = \${$INPUT_ARG_NAME};\nunset(\${$INPUT_ARG_NAME});\n";
        }
        if (self::VALIDATE_ARG_NAME !== $validateArgAlias) {
            $aliases .= "\${$validateArgAlias} = \${$VALIDATE_ARG_NAME};\nunset(\${$VALIDATE_ARG_NAME});\n";
        }
        if (self::DEFAULTS_ARG_NAME !== $materializeArgAlias) {
            $aliases .= "\${$materializeArgAlias} = \${$DEFAULTS_ARG_NAME};\nunset(\${$DEFAULTS_ARG_NAME});\n";
        }
        if ($aliases) {
            $aliases .= "\n";
        }

        return $aliases;
    }

    private function bodyInputGuard(string $inputArgAlias): string
    {
        if ($this->request->isAtLeastPHP('8.0')) {
            return '';
        }

        $inputGuard =
            <<<PHP
            if (!is_array(\$$inputArgAlias) && !is_object(\$$inputArgAlias)) {
                throw new \InvalidArgumentException(
                    'Input to buildFromInput must be array or object, got ' . gettype(\$$inputArgAlias)
                );
            }\n\n
            PHP;

        return $inputGuard;
    }

    private function bodyInputToObjectConversion(string $inputArgAlias, string $materializeArgAlias, string $arrayToObjectExpr): string
    {
        $inputToObjectConversion = '';

        $isArrayExpr = "\$$inputArgAlias = is_array(\$$inputArgAlias)";
        $fullArrToObjectExpr = "{$arrayToObjectExpr}(\$$inputArgAlias)";

        if ($this->defaults) {
            // In case there are defaults, we will generate the "materializeDefaults" arg,
            // which allows applying defaults to user input before passing it to the validator.
            // So, in case the input is an object, we have to clone it so we don't mutate it.
            // *Make it multiline as it would be too long for a single line*
            $inputToObjectConversion =
                <<<PHP
                $isArrayExpr
                    ? {$fullArrToObjectExpr}
                    : (\$$materializeArgAlias ? clone \$$inputArgAlias : \$$inputArgAlias);\n\n
                PHP;
        } else {
            // When no "materializeDefaults", no need to clone. Keep on a single line
            $inputToObjectConversion =
                <<<PHP
                $isArrayExpr ? {$fullArrToObjectExpr} : \$$inputArgAlias;\n
                PHP;
        }

        return $inputToObjectConversion;
    }

    private function bodyMaterializeDefaultsBlock(string $inputArgAlias, string $materializeArgAlias, string $arrayToObjectExpr): string
    {
        if (!$this->defaults) {
            return '';
        }

        $DEFAULTS_PROP = PropertyNames::DEFAULTS_PROP;

        $materializeDefaultsBlock = 
            <<<PHP
            if (\${$materializeArgAlias}) {
                foreach (self::\${$DEFAULTS_PROP} as \$__k => \$__v) {
                    if (!property_exists(\${$inputArgAlias}, (string) \$__k)) {
                        \${$inputArgAlias}->{\$__k} = (\$__v['type'] ?? null) === 'object'
                            ? {$arrayToObjectExpr}(\$__v['default'])
                            : \$__v['default'];
                    }
                }
            }\n\n
            PHP;

        return $materializeDefaultsBlock;
    }

    private function bodyInputValidation(string $inputArgAlias, string $validateArgAlias): string
    {
        $inputValidation =
            <<<PHP
            if (\${$validateArgAlias}) {
                static::validateInput(\${$inputArgAlias});
            }\n\n
            PHP;

        return $inputValidation;
    }

    private function bodyCreateProvidedOptionalsVar(string $providedOptionalsVarName): string
    {
        return $this->hasOptionalNullable
            ? "\${$providedOptionalsVarName} = [];\n"
            : '';
    }

    private function bodySchemaPropertyVars(string $inputArgAlias, string $providedOptionalsVarName): string
    {
        return $this->schemaProperties->generateInputToTypeConversionCode($inputArgAlias, $providedOptionalsVarName) . "\n\n";
    }

    private function bodyCreateNewInstance(string $objVarName): string
    {
        $requiredProperties = $this->schemaProperties->filter(PropertyCollectionFilterFactory::required());
        $constructorParams = [];
        foreach ($requiredProperties as $requiredProperty) {
            $constructorParams[] = '$' . $requiredProperty->name();
        }
        $paramsStr = join(", ", $constructorParams);
        $createNewInstance = "\${$objVarName} = new self({$paramsStr});\n";

        return $createNewInstance;
    }

    private function bodyAssignOptionalsToInstance(string $objVarName): string
    {
        
        $optionalProperties = $this->schemaProperties->filter(PropertyCollectionFilterFactory::optional());
        $assignments = [];
        foreach ($optionalProperties as $optionalProperty) {
            $name = $optionalProperty->name();
            $assignments[] = "\${$objVarName}->{$name} = \${$name};";
        }
        $assignOptionalsToInstance = join("\n", $assignments) . "\n";

        return $assignOptionalsToInstance;
    }

    private function bodyAssignProvidedOptionalsProperty(string $objVarName, string $providedOptionalsVarName): string
    {
        return $this->hasOptionalNullable
            ? "\${$objVarName}->".PropertyNames::OPTIONALS_PROP." = \${$providedOptionalsVarName};\n"
            : '';        
    }
}
