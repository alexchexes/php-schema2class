<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Simple factory for creating {@see SchemaToClass} instances.
 *
 * Exists mainly so consumers can override the factory to inject a subclass or
 * decorate the generated object.
 */
class SchemaToClassFactory
{
    public function build(WriterInterface $writer, OutputInterface $output): SchemaToClass
    {
        return new SchemaToClass($writer, $output);
    }
}