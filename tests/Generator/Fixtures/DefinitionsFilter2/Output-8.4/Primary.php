<?php

declare(strict_types=1);

namespace Ns\DefinitionsFilter2_8_4;

class Primary
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'dep' => [
                '$ref' => '#/definitions/Dependency',
            ],
            'helper' => [
                '$ref' => '#/$defs/SupportChain',
            ],
            'inlineObject' => [
                'type' => 'object',
                'properties' => [
                    'label' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
        'required' => [
            'dep',
            'helper',
        ],
        'definitions' => [
            'Dependency' => [
                'type' => 'object',
                'properties' => [
                    'leaf' => [
                        '$ref' => '#/definitions/Leaf',
                    ],
                ],
                'required' => [
                    'leaf',
                ],
            ],
            'Leaf' => [
                'type' => 'object',
                'properties' => [
                    'value' => [
                        'type' => 'string',
                    ],
                ],
                'required' => [
                    'value',
                ],
            ],
            'Support' => [
                'type' => 'object',
                'properties' => [
                    'detail' => [
                        'type' => 'string',
                    ],
                ],
                'required' => [
                    'detail',
                ],
            ],
            'SupportChain' => [
                'type' => 'object',
                'properties' => [
                    'support' => [
                        '$ref' => '#/$defs/Support',
                    ],
                    'extra' => [
                        '$ref' => '#/definitions/Dependency',
                    ],
                ],
                'required' => [
                    'support',
                ],
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'dep' => 'dep',
        'helper' => 'helper',
        'inlineObject' => 'inlineObject',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private Dependency $dep;

    private SupportChain $helper;

    private ?PrimaryInlineObject $inlineObject = null;

    public function __construct(Dependency $dep, SupportChain $helper, ?PrimaryInlineObject $inlineObject = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->dep = $dep;
        $this->helper = $helper;
        $this->inlineObject = $inlineObject;
    }

    /**
     * Object (`stdClass`) or array with name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return an associative array instead of `stdClass` object.
     */
    public function getAdditionalProperties(bool $asArray = true): \stdClass|array
    {
        return $asArray
            ? json_decode(json_encode($this->_additionalProperties), true)
            : $this->_additionalProperties;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param \stdClass|array $additionalProperties Map of property name/value pairs to add.
     */
    public function withAdditionalProperties(\stdClass|array $additionalProperties): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $clone;
    }

    /**
     * Removes all extra properties not specified in the schema.
     */
    public function withoutAdditionalProperties(): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = new \stdClass();
        return $clone;
    }

    public function getDep(): Dependency
    {
        return $this->dep;
    }

    public function withDep(Dependency $dep): self
    {
        $clone = clone $this;
        $clone->dep = $dep;

        return $clone;
    }

    public function getHelper(): SupportChain
    {
        return $this->helper;
    }

    public function withHelper(SupportChain $helper): self
    {
        $clone = clone $this;
        $clone->helper = $helper;

        return $clone;
    }

    public function getInlineObject(): ?PrimaryInlineObject
    {
        return $this->inlineObject ?? null;
    }

    public function withInlineObject(PrimaryInlineObject $inlineObject): self
    {
        $clone = clone $this;
        $clone->inlineObject = $inlineObject;

        return $clone;
    }

    public function withoutInlineObject(): self
    {
        $clone = clone $this;
        unset($clone->inlineObject);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Primary Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): Primary
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $dep = Dependency::fromInput($input->{'dep'}, $validate);
        $helper = SupportChain::fromInput($input->{'helper'}, $validate);
        $inlineObject = isset($input->{'inlineObject'})
            ? PrimaryInlineObject::fromInput($input->{'inlineObject'}, $validate)
            : null;

        $obj = new self($dep, $helper, $inlineObject);

        $_additionalProperties = array_diff_key(get_object_vars($input), self::$_namesMap);
        if (!empty($_additionalProperties)) {
            $obj->_additionalProperties = (object) $_additionalProperties;
        }

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        $output['dep'] = $this->dep->toArray();
        $output['helper'] = $this->helper->toArray();
        if (isset($this->inlineObject)) {
            $output['inlineObject'] = $this->inlineObject->toArray();
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
        $output = $this->_additionalProperties;

        $output->{'dep'} = $this->dep->toStdClass();
        $output->{'helper'} = $this->helper->toStdClass();
        if (isset($this->inlineObject)) {
            $output->{'inlineObject'} = $this->inlineObject->toStdClass();
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
    public static function validateInput(array|object $input, bool $return = false): bool
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(
                fn (array $e): string => ($e["property"] ? $e["property"] . ": " : "") . $e["message"],
                $validator->getErrors(),
            );
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        $this->_additionalProperties = json_decode(json_encode($this->_additionalProperties));

        $this->dep = clone $this->dep;
        $this->helper = clone $this->helper;
        if (isset($this->inlineObject)) {
            $this->inlineObject = clone $this->inlineObject;
        }
    }
}
