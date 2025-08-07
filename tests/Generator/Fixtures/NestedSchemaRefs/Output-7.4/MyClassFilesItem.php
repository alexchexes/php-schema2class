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

    private ?string $input = null;

    private ?OptionsObject $options = null;

    public function __construct(?string $_input = null, ?OptionsObject $options = null)
    {
        $this->input = $_input;
        $this->options = $options;
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    public function withInput(string $_input): self
    {
        $clone = clone $this;
        $clone->input = $_input;

        return $clone;
    }

    public function withoutInput(): self
    {
        $clone = clone $this;
        unset($clone->input);

        return $clone;
    }

    public function getOptions(): ?OptionsObject
    {
        return $this->options;
    }

    public function withOptions(OptionsObject $options): self
    {
        $clone = clone $this;
        $clone->options = $options;

        return $clone;
    }

    public function withoutOptions(): self
    {
        $clone = clone $this;
        unset($clone->options);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
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

        $obj = new self($_input, $options);
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
     * Validates the current instance against its schema
     *
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
     * @throws \InvalidArgumentException
     */
    public function validate(bool $return = false): bool
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
