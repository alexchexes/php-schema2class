<?php

namespace Ns\NonObjectDefs_5_6;

class Qux
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
                '$ref' => '#/definitions/SkippedDef4',
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
            'SkippedDef4' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/SkippedDef1',
                    ],
                    [
                        '$ref' => '#/definitions/SkippedDef2',
                    ],
                    [
                        '$ref' => '#/definitions/SkippedDef3',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var string|string[]|Foo|Bar|null
     */
    private $grox = null;

    /**
     * @param string|string[]|Foo|Bar|null $grox
     */
    public function __construct($grox = null)
    {
        $this->grox = $grox;
    }

    /**
     * @return string|string[]|Foo|Bar|null
     */
    public function getGrox()
    {
        return $this->grox;
    }

    /**
     * @param string|string[]|Foo|Bar $grox
     * @param bool $validate
     * @return self
     */
    public function withGrox($grox, $validate = true)
    {
        $clone = clone $this;
        $clone->grox = $grox;
        if ($validate) {
            $clone->validate();
        }

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
     * @return Qux Created instance
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

        $grox = isset($input->{'grox'}) ? (((Foo::validateInput($input->{'grox'}, true)) || (Bar::validateInput($input->{'grox'}, true))) ? ((Bar::validateInput($input->{'grox'}, true)) ? Bar::fromInput($input->{'grox'}, $validate) : (((Foo::validateInput($input->{'grox'}, true)) ? Foo::fromInput($input->{'grox'}, $validate) : (null)))) : (((is_array($input->{'grox'})) ? $input->{'grox'} : (((is_string($input->{'grox'})) ? $input->{'grox'} : (null)))))) : null;

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
            if ((is_string($this->grox)) || (is_array($this->grox))) {
                $output['grox'] = $this->grox;
            } else if ((($this->grox instanceof Foo) || ($this->grox instanceof Bar))) {
                $output['grox'] = ($this->grox instanceof Bar) ? ($this->grox->toArray()) : (($this->grox instanceof Foo) ? ($this->grox->toArray()) : (null));
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
            if ((is_string($this->grox)) || (is_array($this->grox))) {
            $output->{'grox'} = $this->grox;
            } else if ((($this->grox instanceof Foo) || ($this->grox instanceof Bar))) {
            $output->{'grox'} = ($this->grox instanceof Bar) ? ($this->grox->toStdClass()) : (($this->grox instanceof Foo) ? ($this->grox->toStdClass()) : (null));
            }
        }

        return $output;
    }

    /**
     * Validates the current instance against its schema
     *
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
     * @throws \InvalidArgumentException
     */
    public function validate($return = false)
    {
        return self::validateInput($this->toStdClass(), $return);
    }

    /**
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
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
            $this->grox = (($this->grox instanceof Foo) || ($this->grox instanceof Bar) ? ($this->grox instanceof Bar ? $this->grox : ($this->grox instanceof Foo ? $this->grox : $this->grox)) : (is_array($this->grox) ? $this->grox : (is_string($this->grox) ? $this->grox : $this->grox)));
        }
    }
}
