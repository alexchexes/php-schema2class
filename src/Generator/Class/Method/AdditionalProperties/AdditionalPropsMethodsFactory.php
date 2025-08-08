<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method\AdditionalProperties;

use Helmich\Schema2Class\Generator\GeneratorRequest;

class AdditionalPropsMethodsFactory
{
    public function __construct(
        private GeneratorRequest $request,
        private array $schema,
        private bool $additionalAllowed,
    )
    {}

    /**
     * @return MethodGenerator[]
     */
    public function generateAll(): array
    {
        if (!$this->additionalAllowed) {
            return [];
        }

        $methodGenerators = [
            (new AdditionalPropGetterFactory($this->request))->generate(),
            (new AdditionalPropSetterFactory($this->request, $this->schema))->generate(),
            (new AdditionalPropUnsetterFactory($this->request, $this->schema))->generate(),
        ];

        return $methodGenerators;
    }
}
