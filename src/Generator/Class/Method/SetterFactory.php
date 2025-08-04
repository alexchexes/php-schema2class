<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\Decorator\PropertyDecoratorInterface;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;
use Helmich\Schema2Class\Generator\Property\Type\ObjectArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\PrimitiveArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\Type\ReferenceArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\TypedArrayProperty;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class SetterFactory
{
    public const CLONE_VAR_NAME = 'clone';

    private bool $mutating;
    private bool $chainable;

    public function __construct(
        private GeneratorRequest $request,
    ) {
        $mutableConfig = $this->request->getMutableSetters();
        $this->mutating = $mutableConfig !== null;
        $this->chainable = $mutableConfig === 'chainable' || $this->mutating === false;
    }

    public function generateSetter(PropertyInterface $property): ?MethodGenerator
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

        // then we fully unwrap any other decorators to be able to see the real type
        $base = $property;
        while ($base instanceof PropertyDecoratorInterface) {
            /** @var PropertyDecoratorInterface $base */
            $base = $base->unwrap();
        }

        $isArray = $base instanceof PrimitiveArrayProperty
            || $base instanceof ObjectArrayProperty
            || $base instanceof ReferenceArrayProperty
            || $base instanceof TypedArrayProperty;

        $addValidation = $this->shouldAddValidation($property, $base, $propTypeHint);

        $docBlock = $this->buildDocBlock($property, $varName, $propAnnotatedType, $addValidation);
        $parameters = $this->buildParams($varName, $propTypeHint, $addValidation);
        $body = $this->generateBody($property, $propName, $varName, $addValidation);

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

    private function buildDocBlock(
        PropertyInterface $property,
        string $varName,
        string $propAnnotatedType,
        bool $addValidation,
    ): DocBlockGenerator
    {
        $tags = [
            new ParamTag($varName, explode('|', $propAnnotatedType))
        ];

        if ($this->chainable) {
            $tags[] = new ReturnTag('self');
        }

        if (PropertyQuery::isDeprecated($property)) {
            $tags[] = new GenericTag('deprecated');
        }

        $docBlock = new DocBlockGenerator(null, null, $tags);
        $docBlock->setWordWrap(false);

        if ($addValidation) {
            $tags[] = new ParamTag('validate', ['bool']);
            $docBlock = new DocBlockGenerator(null, null, $tags);
            $docBlock->setWordWrap(false);
        }

        return $docBlock;
    }

    private function buildParams(string $varName, ?string $propTypeHint, bool $addValidation): array
    {
        $parameters = [new ParameterGenerator($varName, $propTypeHint)];
        if ($addValidation) {
            $validateParam = new ParameterGenerator('validate', 'bool');
            $validateParam->setDefaultValue(true);
            $parameters[] = $validateParam;
        }
        return $parameters;
    }

    private function generateBody(PropertyInterface $property, string $propName, string $varName, bool $addValidation): string
    {
        $propKey = $property->keyStr();

        $validationBlock = '';
        if ($addValidation) {
            $newValidatorExpr = $this->request->getOptions()->getNewValidatorExpr();

            $SCHEMA = PropertyNames::SCHEMA;
            $validationBlock =
                <<<PHP
                if (\$validate) {
                    \$validator = {$newValidatorExpr};
                    \$validator->validate(\$$varName, self::\${$SCHEMA}['properties'][{$propKey}]);
                    if (!\$validator->isValid()) {
                        throw new \\InvalidArgumentException(\$validator->getErrors()[0]['message']);
                    }
                }\n\n
                PHP;
        }

        $OPTIONALS = PropertyNames::OPTIONALS;

        if ($this->mutating) {
            $body =
                $validationBlock .
                "\$this->{$propName} = \$$varName;";

            if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                $body .= "\n\$this->{$OPTIONALS}[{$propKey}] = true;";
            }

            if ($this->chainable) {
                $body .= "\n\nreturn \$this;";
            }
        } else {
            $CLONE = self::CLONE_VAR_NAME;
            $body =
                $validationBlock .
                "\${$CLONE} = clone \$this;\n" .
                "\${$CLONE}->$propName = \$$varName;\n";

            if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                $body .= "\${$CLONE}->{$OPTIONALS}[{$propKey}] = true;\n";
            }

            $body .= "\nreturn \${$CLONE};";
        }

        return $body;
    }

    private function shouldAddValidation(
        PropertyInterface $property,
        PropertyInterface $base,
        ?string $propTypeHint,
    ): bool {
        // When PHP cannot enforce the type itself, we must validate
        if ($propTypeHint === null) {
            return true;
        }

        // Detect if schema has additional validation keywords
        $schema = $property->schema();
        $hasConstraints = $this->schemaHasValidationKeywords($schema);

        if (!$hasConstraints) {
            return false;
        }

        // Some property types are represented as dedicated classes and
        // do not require additional validation as PHP type checks suffice
        if ($base instanceof \Helmich\Schema2Class\Generator\Property\Type\ReferenceProperty
            || $base instanceof \Helmich\Schema2Class\Generator\Property\Type\NestedObjectProperty
            || $base instanceof \Helmich\Schema2Class\Generator\Property\Type\IntersectProperty
            || $base instanceof \Helmich\Schema2Class\Generator\Property\Type\DateProperty
            || ($base instanceof \Helmich\Schema2Class\Generator\Property\Type\StringEnumProperty
                && $base->isComplex())
        ) {
            return false;
        }

        // Arrays and unions are validated when constraints are present
        return true;
    }

    private function schemaHasValidationKeywords(array $schema): bool
    {
        $keywords = [
            'enum', 'const',
            'multipleOf', 'maximum', 'exclusiveMaximum', 'minimum', 'exclusiveMinimum',
            'maxLength', 'minLength', 'pattern', 'format',
            'maxItems', 'minItems', 'uniqueItems', 'items', 'contains', 'minContains', 'maxContains', 'prefixItems',
            'maxProperties', 'minProperties', 'required', 'properties', 'additionalProperties', 'patternProperties', 'propertyNames', 'dependencies', 'dependentRequired', 'dependentSchemas',
            'anyOf', 'oneOf', 'allOf', 'not', 'if', 'then', 'else',
        ];

        foreach ($keywords as $k) {
            if (array_key_exists($k, $schema)) {
                return true;
            }
        }

        return false;
    }
}
