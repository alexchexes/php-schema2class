<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Property;

use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlockGenerator;

/**
 * Factory for creating "$schema" class property that holds the JSON-schema as array used for validation
 */
class ValidationSchemaPropertyFactory
{
    public function __construct(
      private GeneratorRequest $request,
      private array $schema,
    ) {}

    public function generateValidationSchemaProperty(): PropertyGenerator
    {
        // remove metadata like descriptions from schema if such option is set, but keep them
        // for building property documentation
        $validationSchema = $this->schema;
        if ($this->request->getOptions()->getNoSchemaMetadata()) {
            $this->stripSchemaMetadata($validationSchema);
        }

        $prop = new PropertyGenerator(
            PropertyNames::SCHEMA,
            $validationSchema,
            PropertyGenerator::FLAG_PRIVATE | PropertyGenerator::FLAG_STATIC,
        );

        $prop->setDocBlock(new DocBlockGenerator(
            'Schema used to validate input for creating instances of this class',
            null,
            [new GenericTag('var', 'array')],
        ));

        if ($this->request->isAtLeastPHP('7.4')) {
            $prop->setTypeHint('array');
        }
        if ($this->request->getOptions()->getSingleLineSchema()) {
            $prop->setSingleLineDefaultValue(true);
        }

        return $prop;
    }

    private function stripSchemaMetadata(array &$node): void
    {
        $metaFields = [
            'description',
            'title',
            'examples',
            'deprecated',
            'default',
            'readOnly',
            'writeOnly',
            '$id',
            '$schema',
            '$comment',
        ];

        foreach ($node as $key => &$value) {
            if (in_array($key, $metaFields, true)) {
                unset($node[$key]);
                continue;
            }
            if (is_array($value)) {
                $this->stripSchemaMetadata($value);
            }
        }
    }
}