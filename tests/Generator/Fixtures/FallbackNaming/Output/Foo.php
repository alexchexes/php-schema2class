<?php

declare(strict_types=1);

namespace Ns\FallbackNaming;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'required' => [
            'preserved_name',
            'input',
            'validate',
            'obj',
            '_POST',
            '_GET',
            '_REQUEST',
            '_SERVER',
            '_COOKIE',
            '_SESSION',
            '_FILES',
            '_ENV',
            '_GLOBALS',
            'GLOBALS',
        ],
        'properties' => [
            'preserved_name' => [
                'type' => 'string',
            ],
            'input' => [
                'type' => 'string',
            ],
            'validate' => [
                'type' => 'string',
            ],
            'obj' => [
                'type' => 'string',
            ],
            '_POST' => [
                'type' => 'string',
            ],
            '_GET' => [
                'type' => 'string',
            ],
            '_REQUEST' => [
                'type' => 'string',
            ],
            '_SERVER' => [
                'type' => 'string',
            ],
            '_COOKIE' => [
                'type' => 'string',
            ],
            '_SESSION' => [
                'type' => 'string',
            ],
            '_FILES' => [
                'type' => 'string',
            ],
            '_ENV' => [
                'type' => 'string',
            ],
            '_GLOBALS' => [
                'type' => 'string',
            ],
            'GLOBALS' => [
                'type' => 'string',
            ],
        ],
    ];

    /**
     * @var string
     */
    private string $preservedName;

    /**
     * @var string
     */
    private string $input;

    /**
     * @var string
     */
    private string $validate;

    /**
     * @var string
     */
    private string $obj;

    /**
     * @var string
     */
    private string $POST;

    /**
     * @var string
     */
    private string $GET;

    /**
     * @var string
     */
    private string $REQUEST;

    /**
     * @var string
     */
    private string $SERVER;

    /**
     * @var string
     */
    private string $COOKIE;

    /**
     * @var string
     */
    private string $SESSION;

    /**
     * @var string
     */
    private string $FILES;

    /**
     * @var string
     */
    private string $ENV;

    /**
     * @var string
     */
    private string $GLOBALS;

    /**
     * @var string
     */
    private string $GLOBALS_1;

    /**
     * @param string $preservedName
     * @param string $input
     * @param string $validate
     * @param string $obj
     * @param string $POST
     * @param string $GET
     * @param string $REQUEST
     * @param string $SERVER
     * @param string $COOKIE
     * @param string $SESSION
     * @param string $FILES
     * @param string $ENV
     * @param string $GLOBALS
     * @param string $GLOBALS_1
     */
    public function __construct(string $preservedName, string $input, string $validate, string $obj, string $POST, string $GET, string $REQUEST, string $SERVER, string $COOKIE, string $SESSION, string $FILES, string $ENV, string $GLOBALS, string $GLOBALS_1)
    {
        $this->preservedName = $preservedName;
        $this->input = $input;
        $this->validate = $validate;
        $this->obj = $obj;
        $this->POST = $POST;
        $this->GET = $GET;
        $this->REQUEST = $REQUEST;
        $this->SERVER = $SERVER;
        $this->COOKIE = $COOKIE;
        $this->SESSION = $SESSION;
        $this->FILES = $FILES;
        $this->ENV = $ENV;
        $this->GLOBALS = $GLOBALS;
        $this->GLOBALS_1 = $GLOBALS_1;
    }

    /**
     * @return string
     */
    public function getPreservedName() : string
    {
        return $this->preservedName;
    }

    /**
     * @return string
     */
    public function getInput() : string
    {
        return $this->input;
    }

    /**
     * @return string
     */
    public function getValidate() : string
    {
        return $this->validate;
    }

    /**
     * @return string
     */
    public function getObj() : string
    {
        return $this->obj;
    }

    /**
     * @return string
     */
    public function getPOST() : string
    {
        return $this->POST;
    }

    /**
     * @return string
     */
    public function getGET() : string
    {
        return $this->GET;
    }

    /**
     * @return string
     */
    public function getREQUEST() : string
    {
        return $this->REQUEST;
    }

    /**
     * @return string
     */
    public function getSERVER() : string
    {
        return $this->SERVER;
    }

    /**
     * @return string
     */
    public function getCOOKIE() : string
    {
        return $this->COOKIE;
    }

    /**
     * @return string
     */
    public function getSESSION() : string
    {
        return $this->SESSION;
    }

    /**
     * @return string
     */
    public function getFILES() : string
    {
        return $this->FILES;
    }

    /**
     * @return string
     */
    public function getENV() : string
    {
        return $this->ENV;
    }

    /**
     * @return string
     */
    public function getGLOBALS() : string
    {
        return $this->GLOBALS;
    }

    /**
     * @return string
     */
    public function getGLOBALS1() : string
    {
        return $this->GLOBALS_1;
    }

    /**
     * @param string $preservedName
     * @return self
     */
    public function withPreservedName(string $preservedName) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($preservedName, self::$schema['properties']['preserved_name']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->preservedName = $preservedName;

        return $clone;
    }

    /**
     * @param string $input
     * @return self
     */
    public function withInput(string $input) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($input, self::$schema['properties']['input']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->input = $input;

        return $clone;
    }

    /**
     * @param string $validate
     * @return self
     */
    public function withValidate(string $validate) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($validate, self::$schema['properties']['validate']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->validate = $validate;

        return $clone;
    }

    /**
     * @param string $obj
     * @return self
     */
    public function withObj(string $obj) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($obj, self::$schema['properties']['obj']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->obj = $obj;

        return $clone;
    }

    /**
     * @param string $POST
     * @return self
     */
    public function withPOST(string $POST) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($POST, self::$schema['properties']['_POST']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->POST = $POST;

        return $clone;
    }

    /**
     * @param string $GET
     * @return self
     */
    public function withGET(string $GET) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($GET, self::$schema['properties']['_GET']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->GET = $GET;

        return $clone;
    }

    /**
     * @param string $REQUEST
     * @return self
     */
    public function withREQUEST(string $REQUEST) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($REQUEST, self::$schema['properties']['_REQUEST']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->REQUEST = $REQUEST;

        return $clone;
    }

    /**
     * @param string $SERVER
     * @return self
     */
    public function withSERVER(string $SERVER) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($SERVER, self::$schema['properties']['_SERVER']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->SERVER = $SERVER;

        return $clone;
    }

    /**
     * @param string $COOKIE
     * @return self
     */
    public function withCOOKIE(string $COOKIE) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($COOKIE, self::$schema['properties']['_COOKIE']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->COOKIE = $COOKIE;

        return $clone;
    }

    /**
     * @param string $SESSION
     * @return self
     */
    public function withSESSION(string $SESSION) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($SESSION, self::$schema['properties']['_SESSION']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->SESSION = $SESSION;

        return $clone;
    }

    /**
     * @param string $FILES
     * @return self
     */
    public function withFILES(string $FILES) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($FILES, self::$schema['properties']['_FILES']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->FILES = $FILES;

        return $clone;
    }

    /**
     * @param string $ENV
     * @return self
     */
    public function withENV(string $ENV) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($ENV, self::$schema['properties']['_ENV']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->ENV = $ENV;

        return $clone;
    }

    /**
     * @param string $GLOBALS
     * @return self
     */
    public function withGLOBALS(string $GLOBALS) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($GLOBALS, self::$schema['properties']['_GLOBALS']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->GLOBALS = $GLOBALS;

        return $clone;
    }

    /**
     * @param string $GLOBALS_1
     * @return self
     */
    public function withGLOBALS1(string $GLOBALS_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($GLOBALS_1, self::$schema['properties']['GLOBALS']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->GLOBALS_1 = $GLOBALS_1;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $_input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Foo Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $_input, bool $validate = true) : Foo
    {
        $_input = is_array($_input) ? \JsonSchema\Validator::arrayToObjectRecursive($_input) : $_input;
        if ($validate) {
            static::validateInput($_input);
        }

        $preservedName = $_input->{'preserved_name'};
        $input = $_input->{'input'};
        $validate = $_input->{'validate'};
        $obj = $_input->{'obj'};
        $POST = $_input->{'_POST'};
        $GET = $_input->{'_GET'};
        $REQUEST = $_input->{'_REQUEST'};
        $SERVER = $_input->{'_SERVER'};
        $COOKIE = $_input->{'_COOKIE'};
        $SESSION = $_input->{'_SESSION'};
        $FILES = $_input->{'_FILES'};
        $ENV = $_input->{'_ENV'};
        $GLOBALS = $_input->{'_GLOBALS'};
        $GLOBALS_1 = $_input->{'GLOBALS'};

        $obj = new self($preservedName, $input, $validate, $obj, $POST, $GET, $REQUEST, $SERVER, $COOKIE, $SESSION, $FILES, $ENV, $GLOBALS, $GLOBALS_1);

        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray() : array
    {
        $output = [];
        $output['preserved_name'] = $this->preservedName;
        $output['input'] = $this->input;
        $output['validate'] = $this->validate;
        $output['obj'] = $this->obj;
        $output['_POST'] = $this->POST;
        $output['_GET'] = $this->GET;
        $output['_REQUEST'] = $this->REQUEST;
        $output['_SERVER'] = $this->SERVER;
        $output['_COOKIE'] = $this->COOKIE;
        $output['_SESSION'] = $this->SESSION;
        $output['_FILES'] = $this->FILES;
        $output['_ENV'] = $this->ENV;
        $output['_GLOBALS'] = $this->GLOBALS;
        $output['GLOBALS'] = $this->GLOBALS_1;

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
}
