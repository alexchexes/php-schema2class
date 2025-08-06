<?php

declare(strict_types=1);

namespace Ns\NestedSchemaRefs_7_4;

class OptionsObject
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'properties' => [
            'output' => [
                'type' => 'string',
            ],
        ],
    ];

    private ?string $output = null;

    public function __construct(?string $_output = null)
    {
        $this->output = $_output;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function withOutput(string $_output): self
    {
        $clone = clone $this;
        $clone->output = $_output;

        return $clone;
    }

    public function withoutOutput(): self
    {
        $clone = clone $this;
        unset($clone->output);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return OptionsObject Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, bool $validate = true): OptionsObject
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

        $_output = isset($input->{'output'}) ? $input->{'output'} : null;

        $obj = new self($_output);
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
        if (isset($this->output)) {
            $output['output'] = $this->output;
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
        if (isset($this->output)) {
            $output->{'output'} = $this->output;
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
