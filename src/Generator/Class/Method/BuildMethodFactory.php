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

    /** 
     * Generates the whole `buildFromInput` method body and returns it as a string.
     */
    private function generateBody(): string
    {
        $inputArgAlias = $this->ensureUniqueName(self::INPUT_ARG_NAME);
        $validateArgAlias = $this->ensureUniqueName(self::VALIDATE_ARG_NAME);
        $materializeArgAlias = $this->ensureUniqueName(self::DEFAULTS_ARG_NAME);

        $providedOptionalsVarName = $this->ensureUniqueName('_'.PropertyNames::OPTIONALS_PROP);
        $objVarName = $this->ensureUniqueName(self::OBJ_VAR_NAME);

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

    /** 
     * When there are collisions between internal var names and schema properties,
     * it generates a block such as:
     * ```
     *     $_input = $input;
     *     unset($input);
     *     $_validate = $validate;
     *     unset($validate);
     *     $_materializeDefaults = $materializeDefaults;
     *     unset($materializeDefaults);
     * ```
     */
    private function bodyVarAliases(string $inputArgAlias, string $validateArgAlias, string $materializeArgAlias): string
    {
        $aliases = '';

        $setAndUnset = static fn(string $set, string $unset): string =>
            <<<PHP
            \${$set} = \$$unset;
            unset(\$$unset);\n
            PHP;

        if ($inputArgAlias !== self::INPUT_ARG_NAME) {
            $aliases .= $setAndUnset($inputArgAlias, self::INPUT_ARG_NAME);
        }
        if ($validateArgAlias !== self::VALIDATE_ARG_NAME) {
            $aliases .= $setAndUnset($validateArgAlias, self::VALIDATE_ARG_NAME);
        }
        if ($materializeArgAlias !== self::DEFAULTS_ARG_NAME) {
            $aliases .= $setAndUnset($materializeArgAlias, self::DEFAULTS_ARG_NAME);
        }

        if ($aliases) {
            $aliases .= "\n";
        }

        return $aliases;
    }

    /** 
     * When target PHP version is below 8, it generates block such as:
     * ```
     *     if (!is_array($input) && !is_object($input)) {
     *         throw new \InvalidArgumentException(
     *             'Input to buildFromInput must be array or object, got ' . gettype($input)
     *         );
     *     }
     * ```
     */
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

    /** 
     * Generates block like 
     * ```
     *     $input = is_array($input)
     *         ? \JsonSchema\Validator::arrayToObjectRecursive($input)
     *         : ($_materializeDefaults ? clone $input : $input);
     * ```
     * or
     * ```
     * $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
     * ```
     */
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

    /** 
     * When there is "defaults" in schema, it generates block for assigning the
     * default values to input, depending on `materializeDefault` argument, like this:
     * ```
     *     if ($materializeDefaults) {
     *         foreach (self::$_defaults as $__k => $__v) {
     *             if (!property_exists($input, (string) $__k)) {
     *                 $input->{$__k} = ($__v['type'] ?? null) === 'object'
     *                     ? \JsonSchema\Validator::arrayToObjectRecursive($__v['default'])
     *                     : $__v['default'];
     *             }
     *         }
     *     }
     * ```
     */
    private function bodyMaterializeDefaultsBlock(string $inputArgAlias, string $materializeArgAlias, string $arrayToObjectExpr): string
    {
        if (!$this->defaults) {
            return '';
        }

        // let's accept https://wiki.php.net/rfc/arbitrary_string_interpolation so we don't do this:
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

    /** 
     * Generates block like:
     * ```
     *     if ($validate) {
     *         static::validateInput($input);
     *     }
     * ```
     */
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

    /** 
     * If schema has optional nullables, it generates line:
     * ```
     *     $__providedOptionals = [];
     * ```
     */
    private function bodyCreateProvidedOptionalsVar(string $providedOptionalsVarName): string
    {
        return $this->hasOptionalNullable
            ? "\${$providedOptionalsVarName} = [];\n"
            : '';
    }

    /** 
     * Calls `PropertyCollection::generateInputToTypeConversionCode` to generate the whole
     * conversions block where each schema property type mapped to appropriate PHP type
     * and assigned to a local var:
     * ```
     *     $foo = $input->{'foo'};
     *     $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;
     *     $baz = isset($input->{'baz'}) ? Baz::buildFromInput($input->{'baz'}, $validate) : null;
     *     if (property_exists($input, 'qux')) {
     *     //………
     * ```
     */
    private function bodySchemaPropertyVars(string $inputArgAlias, string $providedOptionalsVarName): string
    {
        return $this->schemaProperties->generateInputToTypeConversionCode($inputArgAlias, $providedOptionalsVarName) . "\n\n";
    }

    /**
     * Generates an expression that creates a new instance of the current class,
     * passing all necessary arguments to the constructor in the correct order:
     * ``` 
     *     $obj = new self($city, $street, $country);
     * ```
     */
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

    /**
     * Generates set of assignments of local variables holding schema property values
     * to according properties of the created instance of the class:
     * ``` 
     *     $obj->bar = $bar;
     *     $obj->baz = $baz;
     *     $obj->qux = $qux;
     * ```
     */
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

    /** 
     * Generates an assignment of the local `__providedOptionals` var
     * to the `_providedOptionals` property of the created instance of the class:
     * ```
     *     $obj->_providedOptionals = $__providedOptionals;
     * ```
     */
    private function bodyAssignProvidedOptionalsProperty(string $objVarName, string $providedOptionalsVarName): string
    {
        return $this->hasOptionalNullable
            ? "\${$objVarName}->".PropertyNames::OPTIONALS_PROP." = \${$providedOptionalsVarName};\n"
            : '';        
    }
}
