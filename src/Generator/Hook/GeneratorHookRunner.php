<?php

namespace Helmich\Schema2Class\Generator\Hook;

use Helmich\Schema2Class\Generator\Enum\EnumGenerator;
use Laminas\Code\Generator\ClassGenerator as LaminasClassGenerator;
use Laminas\Code\Generator\FileGenerator;

/**
 * Mixin for generator components that support hooks.
 *
 * Stores hook objects and calls them whenever a new class, enum or file is created.
 */
trait GeneratorHookRunner
{
    /**
     * @var array<array{hook: object, propagateToSubObjects: bool}>
     */
    private array $hooks = [];

    public function withHook(object $hook, bool $propagateToSubObjects = true): self
    {
        $clone          = clone $this;
        $clone->hooks[] = [
            "hook"                  => $hook,
            "propagateToSubObjects" => $propagateToSubObjects,
        ];

        return $clone;
    }

    private function clearNonPropagatingHooks(): void
    {
        $this->hooks = array_filter($this->hooks, fn($hook) => $hook["propagateToSubObjects"]);
    }

    public function onClassCreated(LaminasClassGenerator $class): void
    {
        foreach ($this->hooks as ['hook' => $hook]) {
            if ($hook instanceof ClassCreatedHook) {
                $hook->onClassCreated($class->getName(), $class);
            }
        }
    }

    public function onEnumCreated(string $enumName, EnumGenerator $enum): void
    {
        foreach ($this->hooks as ['hook' => $hook]) {
            if ($hook instanceof EnumCreatedHook) {
                $hook->onEnumCreated($enumName, $enum);
            }
        }
    }

    public function onFileCreated(string $filename, FileGenerator $fileGenerator): void
    {
        foreach ($this->hooks as ['hook' => $hook]) {
            if ($hook instanceof FileCreatedHook) {
                $hook->onFileCreated($filename, $fileGenerator);
            }
        }
    }
}