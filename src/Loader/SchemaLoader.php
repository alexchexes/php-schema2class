<?php
declare(strict_types = 1);
namespace Helmich\Schema2Class\Loader;

use Symfony\Component\Yaml\Yaml;

class SchemaLoader
{
    private static function objectToArrayRecursive(object $obj): array
    {
        $result = [];
        foreach ((array)$obj as $k => $v) {
            if ($v instanceof \stdClass) {
                $result[$k] = self::objectToArrayRecursive($v);
            } elseif (is_array($v)) {
                $result[$k] = array_map(
                    fn($e) => $e instanceof \stdClass ? self::objectToArrayRecursive($e) : $e,
                    $v
                );
            } else {
                $result[$k] = $v;
            }
        }
        return $result;
    }
    /**
     * @param array|string $input
     * @return array
     * @throws LoadingException
     */
    public function loadSchema(array|string|object $input): array
    {
        if (is_array($input)) {
            return $input;
        }

        if (is_object($input)) {
            if (method_exists($input, 'toArray')) {
                return $input->toArray();
            } elseif ($input instanceof \stdClass) {
                return self::objectToArrayRecursive($input);
            }

            throw new LoadingException(
                get_class($input),
                "couldn't transform object to schema array: object is not an instance of 'stdClass' and has no 'toArray()' method"
            );
        }

        $filename = $input;

        if (!file_exists($filename)) {
            throw new LoadingException($filename, "file does not exist");
        }

        $contents = file_get_contents($filename);
        if ($contents === false) {
            throw new LoadingException($filename, "could not open file");
        }

        $pathParts = pathinfo($filename);

        if (!isset($pathParts["extension"])) {
            throw new LoadingException($filename, "could not determine file extension");
        }

        switch ($pathParts['extension']) {
            case 'yml':
            case 'yaml':
                return Yaml::parse($contents);
            case 'json':
                return json_decode($contents, true);
        }

        throw new LoadingException($filename, "unsupported file type: {$pathParts["extension"]}");
    }
}
