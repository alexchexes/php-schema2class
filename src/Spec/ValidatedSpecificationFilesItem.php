<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Spec;

class ValidatedSpecificationFilesItem
{
    private string $targetNamespace;
    private string $targetClass;
    private string $targetDirectory;
    private bool $cleanTargetDirectory;

    /**
     * ValidatedSpecificationFilesItem constructor.
     * @param string $targetNamespace
     * @param string $targetClass
     * @param string $targetDirectory
     */
    public function __construct(string $targetNamespace, string $targetClass, string $targetDirectory, bool $cleanTargetDirectory = false)
    {
        $this->targetNamespace = $targetNamespace;
        $this->targetClass     = $targetClass;
        $this->targetDirectory = $targetDirectory;
        $this->cleanTargetDirectory = $cleanTargetDirectory;
    }

    public static function fromSpecificationFilesItem(
        SpecificationFilesItem $input,
        SpecificationOptions $options,
        string $fallbackNamespace
    ): ValidatedSpecificationFilesItem {
        return new ValidatedSpecificationFilesItem(
            $options->getTargetNamespace() ?? $fallbackNamespace,
            $input->getClassName(),
            $options->getTargetDirectory() ?? '',
            $options->getCleanTargetDirectory(),
        );
    }

    /**
     * @return string
     */
    public function getTargetNamespace(): string
    {
        return $this->targetNamespace;
    }

    /**
     * @return string
     */
    public function getTargetClass(): string
    {
        return $this->targetClass;
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function getCleanTargetDirectory(): bool
    {
        return $this->cleanTargetDirectory;
    }

    public function withTargetClass(string $targetClass): self
    {
        $c              = clone $this;
        $c->targetClass = $targetClass;

        return $c;
    }

    public function withTargetNamespace(string $targetNamespace): self
    {
        $c                = clone $this;
        $c->targetNamespace = $targetNamespace;

        return $c;
    }

    public function withTargetDirectory(string $targetDirectory): self
    {
        $c               = clone $this;
        $c->targetDirectory = $targetDirectory;

        return $c;
    }

    public function withCleanTargetDirectory(bool $cleanTargetDirectory): self
    {
        $c = clone $this;
        $c->cleanTargetDirectory = $cleanTargetDirectory;

        return $c;
    }

}