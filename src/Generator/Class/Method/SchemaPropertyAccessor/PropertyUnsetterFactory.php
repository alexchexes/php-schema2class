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

        if ($this->mutating) {
            // TODO: check why for "mutable" style we generate unsetter only when property is not just optional, but also nullable. Is this necessary?
            if ($property instanceof OptionalPropertyDecorator) {
                return $this->generateMutatingUnsetter($property);
            }
        } else {
            if ($property instanceof OptionalPropertyDecorator) {
                return $this->generateNonMutatingUnsetter($property);
            }
        }

        return null;
    }

    private function generateNonMutatingUnsetter(PropertyInterface $property): MethodGenerator
    {
        $methodName = 'without' . $property->methodName();
        $propKey = $property->keyStr();
        $propName = $property->propName();

        $body = "\$clone = clone \$this;\n";
        $body .= "unset(\$clone->$propName);\n";

        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "unset(\$clone->".PropertyNames::OPTIONALS."[{$propKey}]);\n";
        }

        $body .= "\nreturn \$clone;";

        $unsetMethod = new MethodGenerator(
            name: $methodName,
            parameters: [],
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $body,
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $unsetMethod->setReturnType('self');
        } else {
            $dockBlock = new DocBlockGenerator(null, null, [new ReturnTag('self')]);
            $unsetMethod->setDocBlock($dockBlock);
        }

        return $unsetMethod;
    }

    private function generateMutatingUnsetter(PropertyInterface $property): MethodGenerator
    {
        $methodName = 'unset' . $property->methodName();
        $keyStr = $property->keyStr();
        $propName = $property->propName();

        $body = "\$this->{$propName} = null;\n";
        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "unset(\$this->".PropertyNames::OPTIONALS."[{$keyStr}]);\n";
        }
        if ($this->chainable) {
            $body .= "\nreturn \$this;";
        }

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
}