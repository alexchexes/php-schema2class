<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Enum;

use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\Generator\FileGenerator;
use Helmich\Schema2Class\Generator\Enum\EnumGenerator;
use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;

/**
 * Generates PHP enums or backed enum from JSON Schema `enum` definitions,
 * when possible, or lists of constant values.
 *
 * When the target PHP version allows it and the enum is homogeneous the class
 * will emit a native `enum`; otherwise the generator falls back to traditional
 * type constants within the referencing class.
 */
class SchemaToEnum
{
    private WriterInterface $writer;

    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    /**
     * Checks if a schema can be represented as a native PHP enum.
     *
     * @param array $schema
     */
    public static function canGenerateEnum(array $schema, GeneratorRequest $req): bool
    {
        if (self::areEnumsDisabled($req)) {
            return false;
        }

        if (self::isMixedEnum($schema)) {
            return false;
        }

        $hasCase = false;
        foreach ($schema['enum'] as $case) {
            if ($case === null) {
                continue;
            }
            if (!is_string($case) && !is_int($case)) {
                return false;
            }
            $hasCase = true;
        }

        return $hasCase;
    }

    static public function isMixedEnum(array $schema): bool
    {
        $hasInt = false;
        $hasString = false;

        foreach ($schema['enum'] as $case) {
            if ($case === null) {
                continue;
            }
            if (!is_int($case) && !is_string($case)) {
                return false;
            }
            $hasInt = $hasInt || is_int($case);
            $hasString = $hasString || is_string($case);
        }

        return $hasInt && $hasString;
    }

    public static function areEnumsDisabled(GeneratorRequest $req): bool
    {
        return !$req->isAtLeastPHP('8.1') || $req->getNoEnums();
    }

    public function schemaToEnum(GeneratorRequest $req): void
    {
        if (!$req->isAtLeastPHP('8.1')) {
            throw new GeneratorException('cannot generate enum classes for PHP versions < 8.1');
        }

        if (!self::canGenerateEnum($req->getSchema(), $req)) {
            throw new GeneratorException("cannot generate enum classes for mixed int/string enum values");
        }

        /** @var array<non-empty-string, string|int> $cases */
        $cases = [];
        $hasInt = false;
        $hasString = false;
        foreach ($req->getSchema()["enum"] as $case) {
            if ($case === null) {
                continue;
            }
            if (!is_string($case) && !is_int($case)) {
                throw new GeneratorException("cannot generate enum classes for non-string/non-int enum values");
            }
            $hasInt = $hasInt || is_int($case);
            $hasString = $hasString || is_string($case);

            $name = self::enumCaseName($case);

            // --- guarantee uniqueness ---
            if (isset($cases[$name])) {
                $i = 2;
                $alt = "{$name}__{$i}";
                while (isset($cases[$alt])) {
                    ++$i;
                }
                $name = $alt;              // use the first free "…__n"
            }

            $cases[$name] = $case;
        }

        $cases = self::makeCaseNamesConsistent($cases);

        $typeField = $req->getSchema()["type"] ?? null;
        if (is_array($typeField)) {
            $type = in_array('string', $typeField, true) ? 'string' : 'int';
        } else {
            $type = $typeField === 'string' ? 'string' : 'int';
        }
        $enumName = $req->getTargetNamespace() . "\\" . $req->getTargetClass();
        $enum     = new EnumGenerator($enumName, $type, $cases);

        $req->onEnumCreated($enumName, $enum);

        $filename = $req->getTargetDirectory() . '/' . $req->getTargetClass() . '.php';
        $file     = new FileGenerator();
        $file->setBody($enum->generate());

        $req->onFileCreated($filename, $file);

        $content = $file->getBody();

        $this->writer->writeFile($filename, $content);
    
    }


    /**
     * @param array<non-empty-string, string|int> $cases
     * @return array<non-empty-string, string|int>
     */
    private static function makeCaseNamesConsistent(array $cases): array
    {
        $hasValuePrefix = false;

        foreach ($cases as $name => $value) {
            if (str_starts_with($name, "VALUE_")) {
                $hasValuePrefix = true;
                break;
            }
        }

        if (!$hasValuePrefix) {
            return $cases;
        }

        $newCases = [];
        foreach ($cases as $name => $value) {
            if (str_starts_with($name, "VALUE_")) {
                $newCases[$name] = $value;
            } else {
                $newCases["VALUE_$name"] = $value;
            }
        }

        return $newCases;
    }


    /**
     * @param string|int $value
     * @return non-empty-string
     */
    public static function enumCaseName(string|int $value): string
    {
        // numeric *int* stays as before
        if (is_int($value)) {
            return 'VALUE_' . $value;
        }

        // keep "-" by mapping every non-alnum char to "_"
        /** @var string */
        $clean = preg_replace('/[^a-zA-Z0-9]/', '_', $value);

        // empty after cleaning  →  use literal "EMPTY"
        if ($clean === '') {
            return 'EMPTY';
        }

        // starts with a digit or minus?  →  VALUE_…
        if (preg_match('/^[0-9\-]/', $clean)) {
            return 'VALUE_' . strtoupper($clean);
        }

        // otherwise just upper-cased identifier
        return strtoupper($clean);
    }


}
