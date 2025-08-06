<?php

declare(strict_types=1);

namespace Ns\MaterializeDefaults_8_4;

class NumericKeysObj
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
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
    ];

    private ?string $_0 = null;

    private ?string $_1 = null;

    private ?string $_2 = null;

    public function __construct(?string $_0 = null, ?string $_1 = null, ?string $_2 = null)
    {
        $this->_0 = $_0;
        $this->_1 = $_1;
        $this->_2 = $_2;
    }

    public function get_0(): ?string
    {
        return $this->_0 ?? null;
    }

    public function with_0(string $_0): self
    {
        $clone = clone $this;
        $clone->_0 = $_0;

        return $clone;
    }

    public function without_0(): self
    {
        $clone = clone $this;
        unset($clone->_0);

        return $clone;
    }

    public function get_1(): ?string
    {
        return $this->_1 ?? null;
    }

    public function with_1(string $_1): self
    {
        $clone = clone $this;
        $clone->_1 = $_1;

        return $clone;
    }

    public function without_1(): self
    {
        $clone = clone $this;
        unset($clone->_1);

        return $clone;
    }

    public function get_2(): ?string
    {
        return $this->_2 ?? null;
    }

    public function with_2(string $_2): self
    {
        $clone = clone $this;
        $clone->_2 = $_2;

        return $clone;
    }

    public function without_2(): self
    {
        $clone = clone $this;
        unset($clone->_2);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return NumericKeysObj Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): NumericKeysObj
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }


        $_0 = isset($input->{'0'}) ? $input->{'0'} : null;
        $_1 = isset($input->{'1'}) ? $input->{'1'} : null;
        $_2 = isset($input->{'2'}) ? $input->{'2'} : null;

        $obj = new self($_0, $_1, $_2);
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
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

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @return \stdClass Converted object
     */
    public function toStdClass(): \stdClass
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
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function(array $e): string {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
