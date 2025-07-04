<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Writer;

use RuntimeException;

/**
 * Decorator writer that runs a PHP syntax check on the written file.
 */
class LintingWriter implements WriterInterface
{
    private WriterInterface $inner;

    public function __construct(WriterInterface $inner)
    {
        $this->inner = $inner;
    }

    public function writeFile(string $filename, string $contents): void
    {
        $this->inner->writeFile($filename, $contents);

        $output = [];
        $result = 0;
        exec(PHP_BINARY . ' -l ' . escapeshellarg($filename) . ' 2>&1', $output, $result);
        if ($result !== 0) {
            throw new RuntimeException(
                sprintf("Generated file %s contains syntax errors:\n%s", $filename, implode("\n", $output))
            );
        }
    }
}
