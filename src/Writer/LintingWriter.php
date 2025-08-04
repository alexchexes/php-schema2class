<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Writer;

use Symfony\Component\Process\PhpExecutableFinder;

/**
 * Decorator writer that runs a PHP syntax check on the written file.
 */
class LintingWriter implements WriterInterface
{
    public function __construct(
        private WriterInterface $innerWriter,
        private bool $strict = false,
    ) {}

    public function writeFile(string $filePath, string $contents): void
    {
        $this->innerWriter->writeFile($filePath, $contents);

        // If exec() is unavailable, bail out early
        if (!\function_exists('exec')) {
            $msg = 'Cannot lint: exec() is disabled.';
            if ($this->strict) {
                throw new \RuntimeException($msg);
            } else {
                trigger_error($msg, E_USER_WARNING);
            }
            return;
        }

        // Find a real CLI binary
        $php = (new PhpExecutableFinder())->find(false);
        if ($php === false) {
            $msg = 'Cannot lint: cannot locate a PHP CLI executable.';
            if ($this->strict) {
                throw new \RuntimeException($msg);
            } else {
                trigger_error($msg, E_USER_WARNING);
            }
            return;
        }

        // Run the lint
        $cmd = \escapeshellcmd($php) . ' -l ' . \escapeshellarg($filePath) . ' 2>&1';
        \exec($cmd, $output, $result);

        if ($result !== 0) {
            throw new \RuntimeException(
                "Generated file {$filePath} contains syntax errors:\n" . \implode("\n", $output)
            );
        }
    }
}
