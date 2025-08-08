<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method\SchemaPropertyAccessor;

use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Util\StringUtils;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class PropertySetterFactory
{
    public const CLONE_VAR_NAME = 'clone';
    public const VALIDATE_ARG = 'validate';

    private bool $mutating;
    private bool $chainable;

    public function __construct(
        private GeneratorRequest $request,
    ) {
        $mutableConfig = $this->request->getMutableSetters();
        $this->mutating = $mutableConfig !== null;
        $this->chainable = $mutableConfig === 'chainable' || $this->mutating === false;
    }

    public function generate(PropertyInterface $property): ?MethodGenerator
    {
        if ($this->request->getNoSetters()) {
            return null;
        }

        $prefix = $this->mutating ? 'set' : 'with';
        $methodName = $prefix . $property->methodName();
        $propName = $property->propName();
        $varName = $property->varName();

        // Determine which property should be used for parameter type information.
        // Optional properties are always stored as nullable internally, but
        // setters must only accept `null` when the schema explicitly allows it.
        $paramProperty = ($property instanceof OptionalPropertyDecorator && !$property->allowsNull())
            ? $property->unwrap()
            : $property;

        $propAnnotatedType = $paramProperty->typeAnnotation();
        $propTypeHint = $paramProperty->typeHint();

        // Determine whether setter needs runtime validation and whether
        // validation must be performed against the full schema or just the
        // property fragment.
        $schemaRequiresFullValidation = $this->schemaRequiresFullValidation();
        $propertyNeedsValidation = $property->needsValidation();
        $propertyNeedsFullValidation = $propertyNeedsValidation && $this->schemaHasRef($property->schema());

        $addValidation = $propertyNeedsValidation || $schemaRequiresFullValidation;
        $fullValidation = $schemaRequiresFullValidation || $propertyNeedsFullValidation;

        $docBlock = $this->buildDocBlock($property, $varName, $propAnnotatedType, $propTypeHint, $addValidation);
        $parameters = $this->buildParams($varName, $propTypeHint, $addValidation);
        $body = $this->generateBody($property, $propName, $varName, $addValidation, $fullValidation);

        $methodGen = new MethodGenerator(
            name: $methodName,
            parameters: $parameters,
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $body,
            docBlock: $docBlock
        );

        if ($this->chainable && $this->request->isAtLeastPHP('7.0')) {
            $methodGen->setReturnType('self');
        } elseif (!$this->chainable) {
            if ($this->request->isAtLeastPHP('7.1')) {
                $methodGen->setReturnType('void');
            }
        }

        return $methodGen;
    }

    private function schemaRequiresFullValidation(): bool
    {
        $schema = $this->request->getSchema();
        foreach (['if', 'then', 'else', 'dependencies', 'dependentRequired', 'dependentSchemas'] as $k) {
            if (isset($schema[$k])) {
                return true;
            }
        }
        return false;
    }

    private function schemaHasRef(array $schema): bool
    {
        if (isset($schema['$ref'])) {
            return true;
        }

        foreach ($schema as $value) {
            if (is_array($value) && $this->schemaHasRef($value)) {
                return true;
            }
        }

        return false;
    }

    private function buildDocBlock(
        PropertyInterface $property,
        string $varName,
        string $propAnnotatedType,
        ?string $propTypeHint,
        bool $addValidation,
    ): ?DocBlockGenerator
    {
        $tags = [];
        if ($propAnnotatedType === '') {
            $propAnnotatedType = null;
        } elseif ($propTypeHint !== null && StringUtils::isAnnotationSameAsTypeHint($propAnnotatedType, $propTypeHint)) {
            $propAnnotatedType = null;
        }

        if ($propAnnotatedType) {
            $tags[] = new ParamTag($varName, explode('|', $propAnnotatedType));
        }

        if ($addValidation && !$this->request->isAtLeastPHP('7.0')) {
            $tags[] = new ParamTag('validate', ['bool']);
        }

        if ($this->chainable && !$this->request->isAtLeastPHP('7.0')) {
            $tags[] = new ReturnTag('self');
        }

        if (PropertyQuery::isDeprecated($property)) {
            $tags[] = new GenericTag('deprecated');
        }

        $description = $property->description();
        if ($description === '') {
            $description = null;
        }
        $docBlock = null;
        if ($tags || $description) {
            $docBlock = new DocBlockGenerator(null, $description, $tags);
            $docBlock->setWordWrap(false);
        }

        return $docBlock;
    }

    private function buildParams(string $varName, ?string $propTypeHint, bool $addValidation): array
    {
        $parameters = [new ParameterGenerator($varName, $propTypeHint)];
        if ($addValidation) {
            $type = $this->request->isAtLeastPHP('7.0') ? 'bool' : null;
            $validateParam = new ParameterGenerator('validate', $type);
            $validateParam->setDefaultValue(true);
            $parameters[] = $validateParam;
        }
        return $parameters;
    }

    private function generateBody(
        PropertyInterface $property,
        string $propName,
        string $varName,
        bool $addValidation,
        bool $fullValidation,
    ): string
    {
        $propKey = $property->keyStr();

        $OPTIONALS = PropertyNames::OPTIONALS;
        $VALIDATE = MethodNames::VALIDATE_SELF;
        $VALIDATE_ARG = self::VALIDATE_ARG;
        $CLONE_VAR = self::CLONE_VAR_NAME;

        $validationBlock = '';
        if ($addValidation && !$fullValidation) {
            $newValidatorExpr = $this->request->getOptions()->getNewValidatorExpr();

            $SCHEMA = PropertyNames::SCHEMA;
            $validationBlock =
                <<<PHP
                if (\${$VALIDATE_ARG}) {
                    \$validator = {$newValidatorExpr};
                    \$validator->validate(\$$varName, self::\${$SCHEMA}['properties'][{$propKey}]);
                    if (!\$validator->isValid()) {
                        throw new \InvalidArgumentException(\$validator->getErrors()[0]['message']);
                    }
                }\n\n
                PHP;
        }

        if ($this->mutating) {
            $body =
                $validationBlock .
                "\$this->{$propName} = \$$varName;\n";

            if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                $body .= "\$this->{$OPTIONALS}[{$propKey}] = true;\n";
            }

            if ($addValidation && $fullValidation) {
                $body .= 
                    <<<PHP
                    if (\${$VALIDATE_ARG}) {
                        \$this->{$VALIDATE}();
                    }\n
                    PHP;
            }

            if ($this->chainable) {
                $body .= "\nreturn \$this;";
            }
        } else {
            $body =
                $validationBlock .
                "\${$CLONE_VAR} = clone \$this;\n" .
                "\${$CLONE_VAR}->$propName = \$$varName;\n";

            if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                $body .= "\${$CLONE_VAR}->{$OPTIONALS}[{$propKey}] = true;\n";
            }

            if ($addValidation && $fullValidation) {
                $body .= 
                    <<<PHP
                    if (\${$VALIDATE_ARG}) {
                        \${$CLONE_VAR}->{$VALIDATE}();
                    }\n
                    PHP;
            }

            $body .= "\nreturn \${$CLONE_VAR};";
        }

        return $body;
    }
}
