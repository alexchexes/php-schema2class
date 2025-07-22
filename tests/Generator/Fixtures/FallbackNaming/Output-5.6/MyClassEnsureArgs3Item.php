<?php

namespace Ns\FallbackNaming_5_6;

class MyClassEnsureArgs3Item
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'properties' => [
            'name' => [
                'type' => 'string',
                'default' => '-',
            ],
        ],
    ];

    /**
     * Default values from the schema
     *
     * @var array
     */
    private static $_defaults = [
        'name' => [
            'default' => '-',
        ],
    ];

    /**
     * @var string|null
     */
    private $name = null;

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     * @param bool $validate
     */
    public function withName($name, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($name, self::$schema['properties']['name']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutName()
    {
        $clone = clone $this;
        unset($clone->name);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @param bool $materializeDefaults Apply defaults defined in schema when missing
     * @return MyClassEnsureArgs3Item Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput($input, bool $validate = true, bool $materializeDefaults = false)
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to buildFromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input)
            ? \JsonSchema\Validator::arrayToObjectRecursive($input)
            : ($materializeDefaults ? clone $input : $input);

        if ($materializeDefaults) {
            foreach (self::$_defaults as $__k => $__v) {
                if (!property_exists($input, $__k)) {
                    $input->{$__k} = (isset($__v['type']) && $__v['type'] === 'object')
                       ? \JsonSchema\Validator::arrayToObjectRecursive($__v['default'])
                       : $__v['default'];
                }
            }
        }

        if ($validate) {
            static::validateInput($input);
        }

        $name = isset($input->{'name'}) ? $input->{'name'} : null;

        $obj = new self();
        $obj->name = $name;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return array Converted array
     */
    public function toArray(bool $includeDefaults = false)
    {
        $output = [];
        if (isset($this->name)) {
            $output['name'] = $this->name;
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
    public function toStdClass(bool $includeDefaults = false)
    {
        $output = new \stdClass();
        if (isset($this->name)) {
            $output->{'name'} = $this->name;
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!property_exists($output, $k)) {
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
    public static function validateInput($input, $return = false)
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
