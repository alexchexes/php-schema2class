<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Writer;

/**
 * Decorator writer that runs a PHP syntax check on the written file.
 */
class LintingWriter implements WriterInterface
{
    public function __construct(
        private WriterInterface $innerWriter
    ) {}

    /** 
     * @throws \RuntimeException if written file contents is not valid PHP
     */
    public function writeFile(string $filePath, string $contents): void
    {
        $this->innerWriter->writeFile($filePath, $contents);

        $output = [];
        $result = 0;
        exec(PHP_BINARY . ' -l ' . escapeshellarg($filePath) . ' 2>&1', $output, $result);
        if ($result !== 0) {
            $errors = implode("\n", $output);
            throw new \RuntimeException("Generated file {$filePath} contains syntax errors:\n{$errors}");
        }
    }
}
