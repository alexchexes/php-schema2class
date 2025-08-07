<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Hook;

use Laminas\Code\Generator\ClassGenerator as LaminasClassGenerator;
use Laminas\Code\Generator\MethodGenerator;

/**
 * Hook that injects a preconstructed method into generated class.
 *
 * Registered via {@see GeneratorRequest::withHook}.
 */
readonly class AddMethodHook implements ClassCreatedHook
{
    public function __construct(private MethodGenerator $method)
    {
    }

    function onClassCreated(string $className, LaminasClassGenerator $class): void
    {
        $class->addMethodFromGenerator($this->method);
    }
}