<?php
declare(strict_types = 1);
namespace Helmich\Schema2Class\Loader;

use Symfony\Component\Yaml\Yaml;

class SchemaLoader
{
    /**
     * @param array|string $input
     * @return array
     * @throws LoadingException
     */
    public function loadSchema(array|string $input): array
    {
        if (is_array($input)) {
            return $input;
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
