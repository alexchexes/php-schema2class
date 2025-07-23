<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Hook;

use Laminas\Code\Generator\ClassGenerator as LaminasClassGenerator;

/**
 * Hook that appends an interface to a generated class.
 * 
 * Registered via {@see GeneratorRequest::withInterface}.
 */
readonly class AddInterfaceHook implements ClassCreatedHook
{
    /**
     * @psalm-param class-string $interface
     * @param string $interface
     */
    public function __construct(private string $interface)
    {
    }

    function onClassCreated(string $className, LaminasClassGenerator $class): void
    {
        $interfaces = [...$class->getImplementedInterfaces(), $this->interface];
        $class->setImplementedInterfaces($interfaces);
    }
}