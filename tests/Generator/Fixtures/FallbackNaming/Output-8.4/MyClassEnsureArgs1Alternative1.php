<?php

declare(strict_types=1);

namespace Ns\FallbackNaming_8_4;

class MyClassEnsureArgs1Alternative1
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'properties' => [
            'type' => [
                'type' => 'string',
                'enum' => [
                    'invoice',
                ],
                'default' => 'invoice',
            ],
        ],
    ];

    /**
     * Default values from the schema
     *
     * @var array
     */
    private static array $_defaults = [
        'type' => 'invoice',
    ];

    /**
     * @var MyClassEnsureArgs1Alternative1Type|null
     */
    private ?MyClassEnsureArgs1Alternative1Type $type = null;

    /**
     * @return MyClassEnsureArgs1Alternative1Type|null
     */
    public function getType(): ?MyClassEnsureArgs1Alternative1Type
    {
        return $this->type ?? null;
    }

    /**
     * @param MyClassEnsureArgs1Alternative1Type $type
     * @return self
     */
    public function withType(MyClassEnsureArgs1Alternative1Type $type): self
    {
        $clone = clone $this;
        $clone->type = $type;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutType(): self
    {
        $clone = clone $this;
        unset($clone->type);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @param bool $materializeDefaults Apply defaults defined in schema when missing
     * @return MyClassEnsureArgs1Alternative1 Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true, bool $materializeDefaults = false): MyClassEnsureArgs1Alternative1
    {
        $input = is_array($input)
            ? \JsonSchema\Validator::arrayToObjectRecursive($input)
            : ($materializeDefaults ? clone $input : $input);

        if ($materializeDefaults) {
            foreach (self::$_defaults as $__k => $__v) {
                if (!property_exists($input, $__k)) {
                    $input->{$__k} = is_array($__v) ? \JsonSchema\Validator::arrayToObjectRecursive($__v) : $__v;
                }
            }
        }

        if ($validate) {
            static::validateInput($input);
        }

        $type = isset($input->{'type'}) ? MyClassEnsureArgs1Alternative1Type::from($input->{'type'}) : null;

        $obj = new self();
        $obj->type = $type;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return array Converted array
     */
    public function toArray(bool $includeDefaults = false): array
    {
        $output = [];
        if (isset($this->type)) {
            $output['type'] = ($this->type)->value;
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!array_key_exists($k, $output)) {
                    $output[$k] = $v;
                }
            }
        }

        return $output;
    }

    /**
     * Converts this object back to a stdClass that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return stdClass Converted object
     */
    public function toObject(bool $includeDefaults = false): \stdClass
    {
        $output = new \stdClass();
        if (isset($this->type)) {
            $output->{'type'} = ($this->type)->value;
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!property_exists($output, $k)) {
                    $output->{$k} = $v;
                }
            }
        }

        return $output;
    }

    /**
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result
     * @throws \InvalidArgumentException
     */
    public static function validateInput(array|object $input, bool $return = false): bool
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function(array $e): string {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
