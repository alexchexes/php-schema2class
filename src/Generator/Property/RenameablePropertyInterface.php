<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

interface RenameablePropertyInterface
{
    public function setName(string $name): void;
}

