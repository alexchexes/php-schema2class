<?php
declare(strict_types=1);
namespace Helmich\Schema2Class\Generator;

/**
 * Infers PSR-4 namespaces for generated classes from an existing composer.json.
 *
 * Walks up the directory tree until a composer.json file is found and then
 * matches the directory against the configured PSR-4 mappings.
 */
class NamespaceInferrer
{
    /**
     * @param string $directory
     * @return string
     * @throws GeneratorException
     */
    public function inferNamespaceFromComposerFile(string $directory): string
    {
        $startsWith = function(string $string, string $prefix): bool {
            return substr($string, 0, strlen($prefix)) === $prefix;
        };

        $stripPrefix = function(string $string, string $prefix, int $additional = 0): string {
            return substr($string, strlen($prefix) + $additional);
        };

        $currDir = getcwd() ?: '.';

        if ($directory[0] !== "/") {
            $directory = $currDir . "/" . $directory;
        }

        list($root, $composer) = $this->getComposerJSONForDirectory($directory);

        if (!$startsWith($directory, $root)) {
            throw new GeneratorException("path mismatch: directory $directory is not in $root");
        }

        $relative = $stripPrefix($directory, $root, 1);

        if (isset($composer["autoload"]["psr-4"])) {
            foreach ($composer["autoload"]["psr-4"] as $namespace => $prefix) {
                if ($startsWith($relative, $prefix)) {
                    $pathInRoot = $stripPrefix($relative, $prefix);
                    $relativeNamespace = str_replace("/", "\\", $pathInRoot);
                    $targetNamespace = rtrim($namespace, "\\") . "\\" . ltrim($relativeNamespace, "\\");

                    return $targetNamespace;
                }
            }
        }

        throw new GeneratorException("could not automatically infer namespace from composer.json (hint: use PSR-4 autoloading)");
    }

    /**
     * @param string $directory
     * @return array
     * @throws GeneratorException
     */
    private function getComposerJSONForDirectory(string $directory): array
    {
        $initialDirectory = $directory;

        while ($directory !== "/" && $directory !== "") {
            $filePath = $directory . "/composer.json";
            if (file_exists($filePath)) {
                $contents = file_get_contents($filePath);
                if ($contents === false) {
                    throw new GeneratorException("failed to read contents {$filePath}");
                }
                return [$directory, json_decode($contents, true)];
            }

            $directory = dirname($directory);
        }

        throw new GeneratorException("no composer.json found for directory $initialDirectory");
    }
}
