<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method\SchemaPropertyAccessor;

use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;

class PropertyUnsetterFactory
{
    public const CLONE_VAR = 'clone';
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

        $prefix = $this->mutating ? 'unset' : 'without';
        $methodName = $prefix . $property->methodName();

        $body = $this->generateBody($property);

        $unsetMethod = new MethodGenerator(
            name: $methodName,
            parameters: [],
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $body,
        );

        if ($this->chainable) {
            if ($this->request->isAtLeastPHP('7.0')) {
                $unsetMethod->setReturnType('self');
            } else {
                $dockBlock = new DocBlockGenerator(null, null, [new ReturnTag('self')]);
                $unsetMethod->setDocBlock($dockBlock);
            }
        } elseif ($this->request->isAtLeastPHP('7.1')) {
            $unsetMethod->setReturnType('void');
        }

        return $unsetMethod;
    }

    private function generateBody(PropertyInterface $property): string
    {
        $OPTIONALS = PropertyNames::OPTIONALS;
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

        if ($this->chainable) {
            $bodyParts[] = "\nreturn \${$object};";
        }

        return implode('', $bodyParts);
    }
}