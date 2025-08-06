<?php

namespace Ns\NonObjectDefs_5_6;

class Baz
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'type' => 'object',
        'properties' => [
            'grox' => [
                '$ref' => '#/definitions/SkippedDef3',
            ],
        ],
        'definitions' => [
            'Foo' => [
                'type' => 'object',
                'properties' => [
                    'a' => [
                        '$ref' => '#/definitions/SkippedDef1',
                    ],
                ],
            ],
            'Bar' => [
                'properties' => [
                    'b' => [
                        '$ref' => '#/definitions/SkippedDef2',
                    ],
                ],
            ],
            'SkippedDef1' => [
                'type' => 'string',
            ],
            'SkippedDef2' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
            'SkippedDef3' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/Foo',
                    ],
                    [
                        '$ref' => '#/definitions/Bar',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var Foo|Bar|null
     */
    private $grox = null;

    /**
     * @param Foo|Bar|null $grox
     */
    public function __construct($grox = null)
    {
        $this->grox = $grox;
    }

    /**
     * @return Foo|Bar|null
     */
    public function getGrox()
    {
        return $this->grox;
    }

    /**
     * @param Foo|Bar $grox
     * @param bool $validate
     * @return self
     */
    public function withGrox($grox, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($grox, self::$_schema['properties']['grox']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->grox = $grox;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutGrox()
    {
        $clone = clone $this;
        unset($clone->grox);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Baz Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, $validate = true)
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to fromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $grox = isset($input->{'grox'}) ? ((Bar::validateInput($input->{'grox'}, true)) ? Bar::fromInput($input->{'grox'}, $validate) : (((Foo::validateInput($input->{'grox'}, true)) ? Foo::fromInput($input->{'grox'}, $validate) : (null)))) : null;

        $obj = new self($grox);
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray()
    {
        $output = [];
        if (isset($this->grox)) {
            if (($this->grox instanceof Foo) || ($this->grox instanceof Bar)) {
                $output['grox'] = $this->grox->toArray();
            }
        }

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @return \stdClass Converted object
     */
    public function toStdClass()
    {
        $output = new \stdClass();
        if (isset($this->grox)) {
            if (($this->grox instanceof Foo) || ($this->grox instanceof Bar)) {
            $output->{'grox'} = $this->grox->toStdClass();
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
    public static function validateInput($input, $return = false)
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        if (isset($this->grox)) {
            $this->grox = ($this->grox instanceof Bar ? $this->grox : ($this->grox instanceof Foo ? $this->grox : $this->grox));
        }
    }
}
