<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method\Serialize;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Laminas\Code\Generator\MethodGenerator;

class SerializeMethodFactory
{
    public function __construct(
        private GeneratorRequest $request,
        private PropertyCollection $schemaProperties,
        private array $defaults,
        private bool $additionalsAllowed,
    ) {}

    /** 
     * @return MethodGenerator[]
     */
    public function generateAll(): array
    {
      return [
        (new ToArrayMethodFactory(
            $this->request,
            $this->schemaProperties,
            $this->defaults,
            $this->additionalsAllowed
        ))->generate(),

        (new ToStdClassMethodFactory(
            $this->request, 
            $this->schemaProperties, 
            $this->defaults, 
            $this->additionalsAllowed
        ))->generate(),
      ];
    }
}
