<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method\SchemaPropertyAccessor;

use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Util\SchemaUtils;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class PropertyUnsetterFactory
{
    public const CLONE_VAR = 'clone';
    public const VALIDATE_ARG = 'validate';

    private bool $mutating;
    private bool $chainable;

    public function __construct(
        private GeneratorRequest $request,
        private array $schema,
    ) {
        $mutableConfig = $this->request->getMutableSetters();
        $this->mutating = $mutableConfig !== null;
        $this->chainable = $mutableConfig === 'chainable' || $this->mutating === false;
    }

    /** 
     * Depending on GeneratorRequest options, decides whether unsetter should be mutating, non-mutating,
     * or shouldn't be generated at all, and creates one if needed.
     * If no unsetter should be generated, returns `null`.
     */
    public function generate(PropertyInterface $property): ?MethodGenerator
    {
        if ($this->request->getNoSetters()) {
            return null;
        }
        if (!$property instanceof OptionalPropertyDecorator) {
            return null;
        }

        $addValidation = SchemaUtils::needsRevalidationOnPropertyChange($this->schema, true, true);

        $parameters = $this->buildParams($addValidation);
        $body = $this->generateBody($property, $addValidation);
        $docBlock = $this->buildDocBlock($addValidation);

        $prefix = $this->mutating ? 'unset' : 'without';
        $methodName = $prefix . $property->methodName();

        $methodGen = new MethodGenerator(
            name: $methodName,
            parameters: $parameters,
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $body,
            docBlock: $docBlock,
        );

        // set return type hint if possible
        if ($this->chainable && $this->request->isAtLeastPHP('7.0')) {
            $methodGen->setReturnType('self');
        } elseif (!$this->chainable && $this->request->isAtLeastPHP('7.1')) {
            $methodGen->setReturnType('void');
        }

        return $methodGen;
    }

    private function buildDocBlock(bool $addValidation): ?DocBlockGenerator
    {
        $tags = [];

        if ($addValidation && !$this->request->isAtLeastPHP('7.0')) {
            $tags[] = new ParamTag(self::VALIDATE_ARG, ['bool']);
        }

        if ($this->chainable && !$this->request->isAtLeastPHP('7.0')) {
            $tags[] = new ReturnTag('self');
        }

        $docBlock = null;
        if ($tags) {
            $docBlock = new DocBlockGenerator(null, null, $tags);
        }

        return $docBlock;
    }

    private function buildParams(bool $addValidation): array
    {
        $parameters = [];

        if ($addValidation) {
            $type = $this->request->isAtLeastPHP('7.0') ? 'bool' : null;
            $validateParam = new ParameterGenerator(
                name: self::VALIDATE_ARG,
                type: $type,
                defaultValue: true,
            );
            $parameters[] = $validateParam;
        }

        return $parameters;
    }

    private function generateBody(PropertyInterface $property, bool $addValidation): string
    {
        $OPTIONALS = PropertyNames::OPTIONALS;
        $VALIDATE_SELF = MethodNames::VALIDATE_SELF;
        $VALIDATE_ARG = self::VALIDATE_ARG;
        $CLONE_VAR = self::CLONE_VAR;
        $propKey = $property->keyStr();
        $propName = $property->propName();
        $object = $this->mutating ? 'this' : $CLONE_VAR;

        $bodyParts = [];

        if (!$this->mutating) {
            $bodyParts[] = "\${$CLONE_VAR} = clone \$this;\n";
        }

        $bodyParts[] = "unset(\${$object}->{$propName});\n";

        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $bodyParts[] = "unset(\${$object}->{$OPTIONALS}[{$propKey}]);\n";
        }

        if ($addValidation) {
            $bodyParts[] = 
                <<<PHP
                if (\${$VALIDATE_ARG}) {
                    \${$object}->{$VALIDATE_SELF}();
                }
                PHP;
        }

        if ($this->chainable) {
            $bodyParts[] = "\nreturn \${$object};";
        }

        return implode('', $bodyParts);
    }
}