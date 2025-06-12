<?php

declare(strict_types=1);

namespace Ns\PreservePropertyNames;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'required' => [
            'foo-bar',
            'город',
        ],
        'properties' => [
            'foo-bar' => [
                'type' => 'string',
            ],
            'город' => [
                'type' => 'integer',
            ],
        ],
    ];

    /**
     * @var string
     */
    private string $foobar;

    /**
     * @var int
     */
    private int $gorod;

    /**
     * @param string $foobar
     * @param int $gorod
     */
    public function __construct(string $foobar, int $gorod)
    {
        $this->foobar = $foobar;
        $this->gorod = $gorod;
    }

    /**
     * @return string
     */
    public function getFoobar() : string
    {
        return $this->foobar;
    }

    /**
     * @return int
     */
    public function getGorod() : int
    {
        return $this->gorod;
    }

    /**
     * @param string $foobar
     * @return self
     */
    public function withFoobar(string $foobar) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($foobar, self::$schema['properties']['foo-bar']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->foobar = $foobar;

        return $clone;
    }

    /**
     * @param int $gorod
     * @return self
     */
    public function withGorod(int $gorod) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($gorod, self::$schema['properties']['город']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->gorod = $gorod;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Foo Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : Foo
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to buildFromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foobar = $input->{'foo-bar'};
        $gorod = (int)($input->{'город'});

        $obj = new self($foobar, $gorod);

        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toJson() : array
    {
        $output = [];
        $output['foo-bar'] = $this->foobar;
        $output['город'] = $this->gorod;

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

    public function __clone()
    {
    }
}
