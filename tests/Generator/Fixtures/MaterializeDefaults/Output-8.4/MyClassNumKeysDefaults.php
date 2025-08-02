<?php

declare(strict_types=1);

namespace Ns\MaterializeDefaults_8_4;

class MyClassNumKeysDefaults
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'type' => 'object',
        'properties' => [
            [
                'type' => 'string',
                'default' => 'default for \'0\'',
            ],
            [
                'type' => 'string',
                'default' => 'default for \'1\'',
            ],
            [
                'type' => 'string',
                'default' => 'default for \'2\'',
            ],
        ],
        'default' => [
            
        ],
        'definitions' => [
            'ObjDef' => [
                'type' => 'object',
                'description' => 'Definition of an object with default value that is empty object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
                'default' => [
                    
                ],
            ],
            'ArrayDef' => [
                'type' => 'array',
                'description' => 'Definition of an array with default value that is empty array',
                'items' => [
                    'type' => 'string',
                ],
                'default' => [
                    
                ],
            ],
            'NumericKeysObj' => [
                'type' => 'object',
                'properties' => [
                    [
                        'type' => 'string',
                    ],
                    [
                        'type' => 'string',
                    ],
                    [
                        'type' => 'string',
                    ],
                ],
                'default' => [
                    'a default string for \'0\'',
                    'a default string for \'1\'',
                    'a default string for \'2\'',
                ],
            ],
        ],
    ];

    /**
     * Default values from the schema
     *
     * @var array
     */
    private static array $_defaults = [
        [
            'default' => 'default for \'0\'',
        ],
        [
            'default' => 'default for \'1\'',
        ],
        [
            'default' => 'default for \'2\'',
        ],
    ];

    /**
     * @var string|null
     */
    private ?string $_0 = null;

    /**
     * @var string|null
     */
    private ?string $_1 = null;

    /**
     * @var string|null
     */
    private ?string $_2 = null;

    /**
     * @return string|null
     */
    public function get_0(): ?string
    {
        return $this->_0 ?? null;
    }

    /**
     * @param string $_0
     * @return self
     * @param bool $validate
     */
    public function with_0(string $_0, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_0, self::$schema['properties']['0']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_0 = $_0;

        return $clone;
    }

    /**
     * @return self
     */
    public function without_0(): self
    {
        $clone = clone $this;
        unset($clone->_0);

        return $clone;
    }

    /**
     * @return string|null
     */
    public function get_1(): ?string
    {
        return $this->_1 ?? null;
    }

    /**
     * @param string $_1
     * @return self
     * @param bool $validate
     */
    public function with_1(string $_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_1, self::$schema['properties']['1']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_1 = $_1;

        return $clone;
    }

    /**
     * @return self
     */
    public function without_1(): self
    {
        $clone = clone $this;
        unset($clone->_1);

        return $clone;
    }

    /**
     * @return string|null
     */
    public function get_2(): ?string
    {
        return $this->_2 ?? null;
    }

    /**
     * @param string $_2
     * @return self
     * @param bool $validate
     */
    public function with_2(string $_2, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_2, self::$schema['properties']['2']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_2 = $_2;

        return $clone;
    }

    /**
     * @return self
     */
    public function without_2(): self
    {
        $clone = clone $this;
        unset($clone->_2);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @param bool $materializeDefaults Apply defaults defined in schema when missing
     * @return MyClassNumKeysDefaults Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true, bool $materializeDefaults = false): MyClassNumKeysDefaults
    {
        $input = is_array($input)
            ? \JsonSchema\Validator::arrayToObjectRecursive($input)
            : ($materializeDefaults ? clone $input : $input);

        if ($materializeDefaults) {
            foreach (self::$_defaults as $__k => $__v) {
                if (!property_exists($input, (string) $__k)) {
                    $input->{$__k} = ($__v['type'] ?? null) === 'object'
                        ? \JsonSchema\Validator::arrayToObjectRecursive($__v['default'])
                        : $__v['default'];
                }
            }
        }

        if ($validate) {
            static::validateInput($input);
        }

        $_0 = isset($input->{'0'}) ? $input->{'0'} : null;
        $_1 = isset($input->{'1'}) ? $input->{'1'} : null;
        $_2 = isset($input->{'2'}) ? $input->{'2'} : null;

        $obj = new self();
        $obj->_0 = $_0;
        $obj->_1 = $_1;
        $obj->_2 = $_2;
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
        if (isset($this->_0)) {
            $output['0'] = $this->_0;
        }
        if (isset($this->_1)) {
            $output['1'] = $this->_1;
        }
        if (isset($this->_2)) {
            $output['2'] = $this->_2;
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!array_key_exists($k, $output)) {
                    $output[$k] = $v['default'];
                }
            }
        }

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return \stdClass Converted object
     */
    public function toStdClass(bool $includeDefaults = false): \stdClass
    {
        $output = new \stdClass();
        if (isset($this->_0)) {
            $output->{'0'} = $this->_0;
        }
        if (isset($this->_1)) {
            $output->{'1'} = $this->_1;
        }
        if (isset($this->_2)) {
            $output->{'2'} = $this->_2;
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!property_exists($output, (string) $k)) {
                    $output->{$k} = (isset($v['type']) && $v['type'] === 'object')
                       ? \JsonSchema\Validator::arrayToObjectRecursive($v['default'])
                       : $v['default'];
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
