<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\Class\VariableNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlock\Tag\ThrowsTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class FromInputMethodFactory
{
    public function __construct(
        private GeneratorRequest $request,
        private PropertyCollection $schemaProperties,
        private array $defaults,
        private bool $additionalsAllowed,
    ) {
    }

    public function generate(): MethodGenerator
    {
        $parameterGenerators = $this->buildParams();
        $docBlockGenerators = $this->buildDocBlock();
        $body = $this->generateBody();

        $methodGenerator = new MethodGenerator(
            MethodNames::FROM_INPUT,
            $parameterGenerators,
            MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,
            $body,
            $docBlockGenerators
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $fqcn = $this->request->getTargetNamespace() . '\\' . $this->request->getTargetClass();
            $methodGenerator->setReturnType($fqcn);
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
            new ParameterGenerator(
                name: ArgumentNames::INPUT,
                type: $paramType
            ),
            new ParameterGenerator(
                name: ArgumentNames::VALIDATE,
                type: $this->request->isAtLeastPHP('7.0') ? 'bool' : null,
                defaultValue: true
            ),
        ];

        if ($this->defaults) {
            $parameterGenerators[] = new ParameterGenerator(
                name: ArgumentNames::MATRLZ_DEFAULTS,
                type: $this->request->isAtLeastPHP('7.0') ? 'bool' : null,
                defaultValue: false,
            );
        }

        return $parameterGenerators;
    }

    private function buildDocBlock(): DocBlockGenerator
    {
        $tags = [
            new ParamTag(
                variableName: ArgumentNames::INPUT,
                types: ['array|object'],
                description: 'Input data',
            ),
            new ParamTag(
                variableName: ArgumentNames::VALIDATE,
                types: ['bool'],
                description: 'If `false`, validation against the schema will be skipped.',
            ),
        ];

        if ($this->defaults) {
            $tags[] = new ParamTag(
                variableName: ArgumentNames::MATRLZ_DEFAULTS,
                types: ['bool'],
                description: 'Apply defaults defined in schema when missing',
            );
        }

        $tags[] = new ReturnTag(
            types: [$this->request->getTargetClass()],
            description: 'Created instance',
        );

        $tags[] = new ThrowsTag(
            types: ['\\InvalidArgumentException']
        );

        $docBlock = new DocBlockGenerator(
            shortDescription: 'Builds a new instance from an input array or object',
            tags: $tags,
        );
        $docBlock->setWordWrap(false);

        return $docBlock;
    }

    /**
     * Generates the whole `fromInput` method body and returns it as a string.
     */
    private function generateBody(): string
    {
        $arrayToObjectExpr = $this->request->getOptions()->getArrayToObjectExpr();
        $OBJ_VAR_NAME = VariableNames::OBJ;

        $bodyParts = [
            // in target PHP<8 we can't input specify $input type as `array|object` so we add a guard
            $this->bodyInputGuard(),
            // convert input to object if needed, optionally cloning it when "defaults" are present
            $this->bodyInputToObjectConversion($arrayToObjectExpr),
            // if schema has "defaults", generate "materializeDefaults" block
            $this->bodyMaterializeDefaultsBlock($arrayToObjectExpr),
            // valide input if argument allows
            $this->bodyInputValidation(),
            // create "_providedOptionals" variable to keep track on assigned optional nullables
            $this->bodyCreateProvidedOptionalsVar(),
            // create variables for each property with conversion from JSON value to PHP type
            $this->bodySchemaPropertyVars(),
            // create an instance of the current class
            $this->bodyCreateNewInstance(),
            // assign the `_providedOptionals` if needed
            $this->bodyAssignProvidedOptionalsProperty(),
            // set additional properties if schema allows
            $this->bodySetAdditionalProperties(),
            // return the instance
            "\nreturn \${$OBJ_VAR_NAME};",
        ];

        $body = implode('', $bodyParts);
        return $body;
    }

    /**
     * When target PHP version is below 8, it generates block such as:
     * ```
     *     if (!is_array($input) && !is_object($input)) {
     *         throw new \InvalidArgumentException(
     *             'Input to fromInput must be array or object, got ' . gettype($input)
     *         );
     *     }
     * ```
     */
    private function bodyInputGuard(): string
    {
        $INPUT_ARG = ArgumentNames::INPUT;

        if ($this->request->isAtLeastPHP('8.0')) {
            return '';
        }

        $FROM_INPUT = MethodNames::FROM_INPUT;

        $inputGuard =
            <<<PHP
            if (!is_array(\$$INPUT_ARG) && !is_object(\$$INPUT_ARG)) {
                throw new \InvalidArgumentException(
                    'Input to {$FROM_INPUT} must be array or object, got ' . gettype(\$$INPUT_ARG)
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
    private function bodyInputToObjectConversion(string $arrayToObjectExpr): string
    {
        $INPUT_ARG_NAME = ArgumentNames::INPUT;
        $DEFAULTS_ARG_NAME = ArgumentNames::MATRLZ_DEFAULTS;

        $inputToObjectConversion = '';

        $isArrayExpr = "\$$INPUT_ARG_NAME = is_array(\$$INPUT_ARG_NAME)";
        $fullArrToObjectExpr = "{$arrayToObjectExpr}(\$$INPUT_ARG_NAME)";

        if ($this->defaults) {
            // In case there are defaults, we will generate the "materializeDefaults" arg,
            // which allows applying defaults to user input before passing it to the validator.
            // So, in case the input is an object, we have to clone it so we don't mutate it.
            // *Make it multiline as it would be too long for a single line*
            $inputToObjectConversion =
                <<<PHP
                $isArrayExpr
                    ? {$fullArrToObjectExpr}
                    : (\$$DEFAULTS_ARG_NAME ? clone \$$INPUT_ARG_NAME : \$$INPUT_ARG_NAME);\n\n
                PHP;
        } else {
            // When no "materializeDefaults", no need to clone. Keep on a single line
            $inputToObjectConversion =
                <<<PHP
                $isArrayExpr ? {$fullArrToObjectExpr} : \$$INPUT_ARG_NAME;\n
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
    private function bodyMaterializeDefaultsBlock(string $arrayToObjectExpr): string
    {
        if (!$this->defaults) {
            return '';
        }

        // let's accept https://wiki.php.net/rfc/arbitrary_string_interpolation so we don't do this:
        $DEFAULTS_PROP = PropertyNames::DEFAULTS;
        $INPUT_ARG = ArgumentNames::INPUT;
        $DEFAULTS_ARG = ArgumentNames::MATRLZ_DEFAULTS;

        $materializeDefaultsBlock =
            <<<PHP
            if (\${$DEFAULTS_ARG}) {
                foreach (self::\${$DEFAULTS_PROP} as \$__k => \$__v) {
                    if (!property_exists(\${$INPUT_ARG}, (string) \$__k)) {
                        \${$INPUT_ARG}->{\$__k} = (\$__v['type'] ?? null) === 'object'
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
    private function bodyInputValidation(): string
    {
        $VALIDATE_INPUT = MethodNames::VALIDATE_INPUT;
        $INPUT_ARG = ArgumentNames::INPUT;
        $VALIDATE_ARG = ArgumentNames::VALIDATE;

        $inputValidation =
            <<<PHP
            if (\${$VALIDATE_ARG}) {
                static::{$VALIDATE_INPUT}(\${$INPUT_ARG});
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
    private function bodyCreateProvidedOptionalsVar(): string
    {
        $OPTIONALS_VAR_NAME = VariableNames::PROVIDED_OPTIONALS;
        return $this->schemaProperties->hasOptionalNullable()
            ? "\${$OPTIONALS_VAR_NAME} = [];\n"
            : '';
    }

    /**
     * Calls `PropertyCollection::generateInputToTypeConversionCode` to generate the whole
     * conversions block where each schema property type mapped to appropriate PHP type
     * and assigned to a local var:
     * ```
     *     $foo = $input->{'foo'};
     *     $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;
     *     $baz = isset($input->{'baz'}) ? Baz::fromInput($input->{'baz'}, $validate) : null;
     *     if (property_exists($input, 'qux')) {
     *     //………
     * ```
     */
    private function bodySchemaPropertyVars(): string
    {
        $requiredProperties = $this->schemaProperties->filter(PropertyCollectionFilterFactory::onlyRequired());
        $optionalProperties = $this->schemaProperties->filter(PropertyCollectionFilterFactory::onlyOptional());

        $parts = array_filter([
            $requiredProperties->generateInputToTypeConversionCode(),
            $optionalProperties->generateInputToTypeConversionCode(),
        ]);

        return join("\n", $parts) . "\n\n";
    }

    /**
     * Generates an expression that creates a new instance of the current class,
     * passing all arguments to the constructor in the correct order:
     * ```
     *     $obj = new self($city, $street, $country);
     * ```
     */
    private function bodyCreateNewInstance(): string
    {
        $OBJ_VAR = VariableNames::OBJ;
        $requiredProperties = $this->schemaProperties->filter(PropertyCollectionFilterFactory::onlyRequired());
        $optionalProperties = $this->schemaProperties->filter(PropertyCollectionFilterFactory::onlyOptional());
        $constructorParams = [];

        foreach ([...$requiredProperties, ...$optionalProperties] as $requiredProperty) {
            $constructorParams[] = '$' . $requiredProperty->varName();
        }

        $paramsStr = join(", ", $constructorParams);
        if (mb_strlen($paramsStr) > 70) {
            $paramsStr = "\n    " . join(",\n    ", $constructorParams) . "\n";
        }

        $createNewInstance = "\${$OBJ_VAR} = new self({$paramsStr});\n";

        return $createNewInstance;
    }

    /**
     * Generates an assignment of the local `__providedOptionals` var
     * to the `_providedOptionals` property of the created instance of the class:
     * ```
     *     $obj->_providedOptionals = $__providedOptionals;
     * ```
     */
    private function bodyAssignProvidedOptionalsProperty(): string
    {
        $OBJ_VAR = VariableNames::OBJ;
        $OPTIONALS_PROP = PropertyNames::PROVIDED_OPTIONALS;
        $OPTIONALS_VAR = VariableNames::PROVIDED_OPTIONALS;

        return $this->schemaProperties->hasOptionalNullable()
            ? "\${$OBJ_VAR}->{$OPTIONALS_PROP} = \${$OPTIONALS_VAR};\n"
            : '';
    }

    private function bodySetAdditionalProperties(): string
    {
        if (!$this->additionalsAllowed) {
            return '';
        }

        $ADDIT_PROPS_PROP = PropertyNames::ADDITIONAL_PROPS;
        $ADDIT_PROPS_VAR = VariableNames::ADDITIONAL_PROPS;
        $INPUT_ARG = ArgumentNames::INPUT;
        $NAMES_MAP = PropertyNames::NAMES_MAP;
        $OBJ_VAR = VariableNames::OBJ;

        return
            <<<PHP
            \n\${$ADDIT_PROPS_VAR} = array_diff_key(get_object_vars(\${$INPUT_ARG}), self::\${$NAMES_MAP});
            if (!empty(\${$ADDIT_PROPS_VAR})) {
                \${$OBJ_VAR}->{$ADDIT_PROPS_PROP} = (object) \${$ADDIT_PROPS_VAR};
            }\n
            PHP;
    }
}
