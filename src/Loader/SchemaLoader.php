<?php
declare(strict_types = 1);
namespace Helmich\Schema2Class\Loader;

use Symfony\Component\Yaml\Yaml;

class SchemaLoader
{
    public const RAW_KEY = '__schema2class_raw';
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
     * @param array|string|\stdClass $input
     * @return array
     * @throws LoadingException
     */
    public function loadSchema(array|string|\stdClass $input): array
    {
        if (is_array($input)) {
            return $input;
        }

        if ($input instanceof \stdClass) {
            return array_merge(self::objectToArrayRecursive($input), [self::RAW_KEY => $input]);
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
                $raw = json_decode($contents); // stdClass or array
                return array_merge(self::objectToArrayRecursive($raw), [self::RAW_KEY => $raw]);
        }

        throw new LoadingException($filename, "unsupported file type: {$pathParts["extension"]}");
    }
}
