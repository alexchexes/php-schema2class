<?php

declare(strict_types=1);

namespace Ns\NestedSchemaRefs_7_4;

class MyClassFilesItem
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'properties' => [
            'input' => [
                'type' => 'string',
            ],
            'options' => [
                '$ref' => '#/definitions/OptionsObject',
            ],
        ],
        'definitions' => [
            'OptionsObject' => [
                'properties' => [
                    'output' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var string|null
     */
    private ?string $input = null;

    /**
     * @var OptionsObject|null
     */
    private ?OptionsObject $options = null;

    /**
     * @return string|null
     */
    public function getInput(): ?string
    {
        return $this->input ?? null;
    }

    /**
     * @param string $_input
     * @return self
     */
    public function withInput(string $_input): self
    {
        $clone = clone $this;
        $clone->input = $_input;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutInput(): self
    {
        $clone = clone $this;
        unset($clone->input);

        return $clone;
    }

    /**
     * @return OptionsObject|null
     */
    public function getOptions(): ?OptionsObject
    {
        return $this->options ?? null;
    }

    /**
     * @param OptionsObject $options
     * @return self
     */
    public function withOptions(OptionsObject $options): self
    {
        $clone = clone $this;
        $clone->options = $options;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptions(): self
    {
        $clone = clone $this;
        unset($clone->options);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyClassFilesItem Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, bool $validate = true): MyClassFilesItem
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

        $_input = isset($input->{'input'}) ? $input->{'input'} : null;
        $options = isset($input->{'options'}) ? OptionsObject::fromInput($input->{'options'}, $validate) : null;

        $obj = new self();
        $obj->input = $_input;
        $obj->options = $options;
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
        if (isset($this->input)) {
            $output['input'] = $this->input;
        }
        if (isset($this->options)) {
            $output['options'] = $this->options->toArray();
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
        if (isset($this->input)) {
            $output->{'input'} = $this->input;
        }
        if (isset($this->options)) {
            $output->{'options'} = $this->options->toStdClass();
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
    public static function validateInput($input, bool $return = false): bool
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
