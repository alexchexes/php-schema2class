<?php

declare(strict_types=1);

namespace Ns\NestedTypedArrayProperty_8_4;

class Fio
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'type' => 'object',
        'properties' => [
            'bar' => [
                'type' => [
                    'null',
                    'string',
                ],
            ],
        ],
    ];

    /**
     * Map of optional nullable property names that were explicitly set to `null`
     *
     * @var array<string,true>
     */
    private array $_explicitNulls = [];

    /**
     * @var string|null
     */
    private ?string $bar = null;

    /**
     * @return string|null
     */
    public function getBar() : ?string
    {
        return $this->bar ?? null;
    }

    /**
     * @param string $bar
     * @return self
     * @param bool $validate
     */
    public function withBar(string $bar, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bar, self::$schema['properties']['bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->bar = $bar;
        $clone->_explicitNulls['bar'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBar() : self
    {
        $clone = clone $this;
        unset($clone->bar);
        unset($clone->_explicitNulls['bar']);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Fio Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : Fio
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $__explicitNulls = [];
        $bar = property_exists($input, 'bar') ? $input->{'bar'} : null;
        if (property_exists($input, 'bar')) {
            $__explicitNulls['bar'] = true;
        }

        $obj = new self();
        $obj->bar = $bar;
        $obj->_explicitNulls = $__explicitNulls;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray() : array
    {
        $output = [];
        if (isset($this->bar) || array_key_exists('bar', $this->_explicitNulls)) {
            $output['bar'] = $this->bar;
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
    public static function validateInput(array|object $input, bool $return = false) : bool
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

    /**
     * Checks if an optional nullable property was explicitly set to `null`
     *
     * @param string $propertyName property name as appears in the schema
     * @return bool
     */
    public function isExplicitNull(string $propertyName) : bool
    {
        return array_key_exists($propertyName, $this->_explicitNulls);
    }
}
