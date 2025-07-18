<?php

declare(strict_types=1);

namespace Ns\DefaultValue_7_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'required' => [
            
        ],
        'properties' => [
            'limit' => [
                'type' => 'integer',
                'default' => 10000,
                'minimum' => 1,
            ],
            'skip' => [
                'type' => 'integer',
                'default' => 0,
            ],
        ],
    ];

    /**
     * Default values defined in the schema
     *
     * @var array<string,mixed>
     */
    private static array $defaults = [
        'limit' => 10000,
        'skip' => 0,
    ];

    /**
     * @var int|null
     */
    private ?int $limit = null;

    /**
     * @var int|null
     */
    private ?int $skip = null;

    /**
     * @return int
     */
    public function getLimit() : int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getSkip() : int
    {
        return $this->skip;
    }

    /**
     * @param int $limit
     * @return self
     * @param bool $validate
     */
    public function withLimit(int $limit, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($limit, self::$schema['properties']['limit']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->limit = $limit;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutLimit() : self
    {
        $clone = clone $this;
        $clone->limit = 10000;

        return $clone;
    }

    /**
     * @param int $skip
     * @return self
     * @param bool $validate
     */
    public function withSkip(int $skip, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($skip, self::$schema['properties']['skip']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->skip = $skip;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutSkip() : self
    {
        $clone = clone $this;
        $clone->skip = 0;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @param bool $materializeDefaults Apply defaults from schema when missing
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput($input, bool $validate = true, bool $materializeDefaults = false) : MyClass
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to buildFromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $__defaultsApplied = [];
        if ($materializeDefaults) {
            foreach (self::$defaults as $__k => $__v) {
                if (!property_exists($input, $__k)) {
                    $input->{$__k} = is_array($__v) ? \JsonSchema\Validator::arrayToObjectRecursive($__v) : $__v;
                    $__defaultsApplied[$__k] = true;
                }
            }
        }
        if ($validate) {
            static::validateInput($input);
        }

        $limit = isset($input->{'limit'}) ? $input->{'limit'} : null;
        $skip = isset($input->{'skip'}) ? $input->{'skip'} : null;

        $obj = new self();
        $obj->limit = $limit;
        $obj->skip = $skip;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return array Converted array
     */
    public function toArray(bool $includeDefaults = false) : array
    {
        $output = [];
        if (isset($this->limit)) {
            $output['limit'] = $this->limit;
        }
        if (isset($this->skip)) {
            $output['skip'] = $this->skip;
        }

        if ($includeDefaults) {
            foreach (self::$defaults as $k => $v) {
                if (!array_key_exists($k, $output)) {
                    $output[$k] = $v;
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
    public static function validateInput($input, bool $return = false) : bool
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
