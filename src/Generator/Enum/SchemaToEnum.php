<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Enum;

use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\Generator\FileGenerator;
use Helmich\Schema2Class\Generator\Enum\EnumGenerator;
use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Util\StringUtils;

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

    /** 
     * Checks if user config disables enum either via noEnums option or
     * due to target PHP version being set that doesn't support Enums
     */
    public static function areEnumsDisabled(GeneratorRequest $req): bool
    {
        return !$req->isAtLeastPHP('8.1') || $req->getNoEnums();
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

    public function schemaToEnum(GeneratorRequest $req): void
    {
        if (!$req->isAtLeastPHP('8.1')) {
            throw new GeneratorException('cannot generate enum classes for PHP versions < 8.1');
        }

        if (!self::canGenerateEnum($req->getSchema(), $req)) {
            throw new GeneratorException("cannot generate enum classes for mixed int/string enum values");
        }

        /** @var array<non-empty-string, string|int> $casesFiltered */
        $casesFiltered = [];
        $hasInt = false;
        $hasString = false;
        $cases = $req->getSchema()["enum"];

        // sort to get deterministic results when we're renaming
        $moveNonAlfanum = fn(mixed $str) => is_string($str) ? preg_replace('/^([\W]*)(.+)/', '$2$1', $str) : $str;
        usort($cases, fn($a, $b) => $moveNonAlfanum($a) <=> $moveNonAlfanum($b));

        foreach ($cases as $case) {
            if ($case === null) {
                continue;
            }
            if (in_array($case, $casesFiltered, true)) {
                continue;
            }
            if (!is_string($case) && !is_int($case)) {
                throw new GeneratorException("cannot generate enum classes for non-string/non-int enum values");
            }
            $hasInt = $hasInt || is_int($case);
            $hasString = $hasString || is_string($case);

            $name = self::enumCaseName($case);

            // guarantee uniqueness: try to prepend "_" if doesn't start with it
            // already, or append "__#"
            if (isset($casesFiltered[$name])) {
                $i = 2;
                $alt = str_starts_with($name, '_') ? "{$name}__{$i}" : "_{$name}";
                while (isset($casesFiltered[$alt])) {
                    ++$i;
                    $alt = "{$name}__{$i}";
                }
                $name = $alt;
            }

            $casesFiltered[$name] = $case;
        }

        $typeField = $req->getSchema()["type"] ?? null;

        if (is_array($typeField)) {
            $type = in_array('string', $typeField, true) ? 'string' : 'int';
        } else {
            $type = $typeField === 'string' ? 'string' : 'int';
        }

        $enumName = $req->getTargetNamespace() . "\\" . $req->getTargetClass();
        $enum     = new EnumGenerator($enumName, $type, $casesFiltered);

        $req->onEnumCreated($enumName, $enum);

        $filename = $req->getTargetDirectory() . '/' . $req->getTargetClass() . '.php';
        $file     = new FileGenerator();
        $file->setBody($enum->generate());

        $req->onFileCreated($filename, $file);

        $content = $file->getBody();

        $this->writer->writeFile($filename, $content);
    
    }

    /**
     * @param string|int $value
     * @return non-empty-string
     */
    public static function enumCaseName(string|int $value): string
    {
        // numeric *int* stays as before
        if (is_int($value)) {
            $suffix = abs($value);
            $intPrefix = $value < 0 ? 'NEGATIVE_' : 'INT_';
            return $intPrefix . $suffix;
        }

        // empty after cleaning  →  use literal "EMPTY"
        if ($value === '') {
            return 'EMPTY';
        }
        if ($value === ' ') {
            return 'SPACE';
        }
        // TODO: add popular chars map?

        $clean = StringUtils::sanitizeIdentifier($value);
        
        if (preg_match('/^-\d+$/', $value)) {
            $clean = 'MINUS' . $clean;
        } elseif (preg_match('/^\d+$/', $value)) {
            $clean .= '_';
        }

        return strtoupper($clean);
    }
}
