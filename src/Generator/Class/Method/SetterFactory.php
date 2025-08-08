<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

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

        // Determine whether setter needs runtime validation.
        $schemaNeedsValidation = $this->rootSchemaRequiresFullValidation();
        $propertyNeedsValidation = $property->needsValidation();
        $propertyNeedsFullValidation = $propertyNeedsValidation && $this->schemaContainsRef($property->schema());

        $requiresFullValidation = $schemaNeedsValidation || $propertyNeedsFullValidation;
        $addValidation = $propertyNeedsValidation || $schemaNeedsValidation;

        $docBlock = $this->buildDocBlock($property, $varName, $propAnnotatedType, $propTypeHint, $addValidation);
        $parameters = $this->buildParams($varName, $propTypeHint, $addValidation);
        $body = $this->generateBody($property, $propName, $varName, $addValidation, $requiresFullValidation);

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
        bool $requiresFullValidation,
    ): string {
        $propKey = $property->keyStr();

        $validationBlock = '';
        if ($addValidation && !$requiresFullValidation) {
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
            if ($requiresFullValidation) {
                $body = "\$this->{$propName} = \$$varName;\n";
                if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                    $body .= "\$this->{$OPTIONALS}[{$propKey}] = true;\n";
                }
                if ($addValidation) {
                    $body .= "if (\$validate) {\n    \$this->validate();\n}\n";
                }
                if ($this->chainable) {
                    $body .= "\nreturn \$this;";
                }
            } else {
                $body =
                    $validationBlock .
                    "\$this->{$propName} = \$$varName;";

                if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                    $body .= "\n\$this->{$OPTIONALS}[{$propKey}] = true;";
                }

                if ($this->chainable) {
                    $body .= "\n\nreturn \$this;";
                }
            }
        } else {
            $CLONE = self::CLONE_VAR_NAME;
            if ($requiresFullValidation) {
                $body =
                    "\${$CLONE} = clone \$this;\n" .
                    "\${$CLONE}->$propName = \$$varName;\n";
                if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                    $body .= "\${$CLONE}->{$OPTIONALS}[{$propKey}] = true;\n";
                }
                if ($addValidation) {
                    $body .= "if (\$validate) {\n    \${$CLONE}->validate();\n}\n";
                }
                $body .= "\nreturn \${$CLONE};";
            } else {
                $body =
                    $validationBlock .
                    "\${$CLONE} = clone \$this;\n" .
                    "\${$CLONE}->$propName = \$$varName;\n";

                if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                    $body .= "\${$CLONE}->{$OPTIONALS}[{$propKey}] = true;\n";
                }

                $body .= "\nreturn \${$CLONE};";
            }
        }

        return $body;
    }

    private function schemaContainsRef(array $schema): bool
    {
        if (isset($schema['$ref'])) {
            return true;
        }

        foreach ($schema as $value) {
            if (is_array($value) && $this->schemaContainsRef($value)) {
                return true;
            }
        }

        return false;
    }

    private function rootSchemaRequiresFullValidation(): bool
    {
        $schema = $this->request->getSchema();

        return isset($schema['if']) || isset($schema['then']) || isset($schema['else'])
            || isset($schema['dependentSchemas']) || isset($schema['dependentRequired'])
            || isset($schema['dependencies']);
    }
}
