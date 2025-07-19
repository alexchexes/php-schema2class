<?php

namespace Ns\FallbackNamingPreserve_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'required' => [
            '_GLOBALS',
            'GLOBALS',
            'GLOBALS_1',
            '_SERVER',
            '_GET',
            '_POST',
            '_FILES',
            '_REQUEST',
            '_SESSION',
            '_ENV',
            '_COOKIE',
            'php_errormsg',
            'http_response_header',
            'argc',
            'argv',
            'input',
            'validate',
            'obj',
            'materializeDefaults',
            'includeDefaults',
            'buildFromInput',
            'toArray',
            'validateInput',
            'schema',
            '_defaults',
            'clone',
            '__construct',
            '__destruct',
            '__get',
            '__set',
            '__call',
            '__isset',
            '__unset',
            '__sleep',
            '__wakeup',
            '__toString',
            '__invoke',
            '__debugInfo',
            '__clone',
            'files',
        ],
        'properties' => [
            '_GLOBALS' => [
                'type' => 'string',
            ],
            'GLOBALS' => [
                'type' => 'string',
            ],
            'GLOBALS_1' => [
                'type' => 'string',
            ],
            '_SERVER' => [
                'type' => 'string',
            ],
            '_GET' => [
                'type' => 'string',
            ],
            '_POST' => [
                'type' => 'string',
            ],
            '_FILES' => [
                'type' => 'string',
            ],
            '_REQUEST' => [
                'type' => 'string',
            ],
            '_SESSION' => [
                'type' => 'string',
            ],
            '_ENV' => [
                'type' => 'string',
            ],
            '_COOKIE' => [
                'type' => 'string',
            ],
            'php_errormsg' => [
                'type' => 'string',
            ],
            'http_response_header' => [
                'type' => 'string',
            ],
            'argc' => [
                'type' => 'string',
            ],
            'argv' => [
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
            'materializeDefaults' => [
                'type' => 'string',
            ],
            'includeDefaults' => [
                'type' => 'string',
            ],
            'testObj' => [
                'type' => 'object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'buildFromInput' => [
                'type' => 'string',
            ],
            'toArray' => [
                'type' => 'string',
            ],
            'validateInput' => [
                'type' => 'string',
            ],
            'schema' => [
                'type' => 'string',
            ],
            '_defaults' => [
                'type' => 'string',
                'default' => 'foo',
            ],
            'clone' => [
                'type' => 'string',
            ],
            '__construct' => [
                'type' => 'string',
            ],
            '__destruct' => [
                'type' => 'string',
            ],
            '__get' => [
                'type' => 'string',
            ],
            '__set' => [
                'type' => 'string',
            ],
            '__call' => [
                'type' => 'string',
            ],
            '__isset' => [
                'type' => 'string',
            ],
            '__unset' => [
                'type' => 'string',
            ],
            '__sleep' => [
                'type' => 'string',
            ],
            '__wakeup' => [
                'type' => 'string',
            ],
            '__toString' => [
                'type' => 'string',
            ],
            '__invoke' => [
                'type' => 'string',
            ],
            '__debugInfo' => [
                'type' => 'string',
            ],
            '__clone' => [
                'type' => 'string',
            ],
            'files' => [
                'type' => 'string',
            ],
        ],
    ];

    /**
     * Default values from the schema
     *
     * @var array
     */
    private static $_defaults = [
        '_defaults' => 'foo',
    ];

    /**
     * @var string
     */
    private $_GLOBALS_1;

    /**
     * @var string
     */
    private $_GLOBALS_2;

    /**
     * @var string
     */
    private $_GLOBALS_1_1;

    /**
     * @var string
     */
    private $_SERVER_1;

    /**
     * @var string
     */
    private $_GET_1;

    /**
     * @var string
     */
    private $_POST_1;

    /**
     * @var string
     */
    private $_FILES_1;

    /**
     * @var string
     */
    private $_REQUEST_1;

    /**
     * @var string
     */
    private $_SESSION_1;

    /**
     * @var string
     */
    private $_ENV_1;

    /**
     * @var string
     */
    private $_COOKIE_1;

    /**
     * @var string
     */
    private $_php_errormsg;

    /**
     * @var string
     */
    private $_http_response_header;

    /**
     * @var string
     */
    private $_argc;

    /**
     * @var string
     */
    private $_argv;

    /**
     * @var string
     */
    private $input;

    /**
     * @var string
     */
    private $validate;

    /**
     * @var string
     */
    private $obj;

    /**
     * @var string
     */
    private $materializeDefaults;

    /**
     * @var string
     */
    private $includeDefaults;

    /**
     * @var MyClassTestObj|null
     */
    private $testObj = null;

    /**
     * @var string
     */
    private $_buildFromInput;

    /**
     * @var string
     */
    private $_toArray;

    /**
     * @var string
     */
    private $_validateInput;

    /**
     * @var string
     */
    private $_schema;

    /**
     * @var string
     */
    private $_defaults_1;

    /**
     * @var string
     */
    private $_clone;

    /**
     * @var string
     */
    private $__construct_1;

    /**
     * @var string
     */
    private $__destruct_1;

    /**
     * @var string
     */
    private $__get_1;

    /**
     * @var string
     */
    private $__set_1;

    /**
     * @var string
     */
    private $__call_1;

    /**
     * @var string
     */
    private $__isset_1;

    /**
     * @var string
     */
    private $__unset_1;

    /**
     * @var string
     */
    private $__sleep_1;

    /**
     * @var string
     */
    private $__wakeup_1;

    /**
     * @var string
     */
    private $__toString_1;

    /**
     * @var string
     */
    private $__invoke_1;

    /**
     * @var string
     */
    private $__debugInfo_1;

    /**
     * @var string
     */
    private $__clone_1;

    /**
     * @var string
     */
    private $files;

    /**
     * @param string $_GLOBALS_1
     * @param string $_GLOBALS_2
     * @param string $_GLOBALS_1_1
     * @param string $_SERVER_1
     * @param string $_GET_1
     * @param string $_POST_1
     * @param string $_FILES_1
     * @param string $_REQUEST_1
     * @param string $_SESSION_1
     * @param string $_ENV_1
     * @param string $_COOKIE_1
     * @param string $_php_errormsg
     * @param string $_http_response_header
     * @param string $_argc
     * @param string $_argv
     * @param string $input
     * @param string $validate
     * @param string $obj
     * @param string $materializeDefaults
     * @param string $includeDefaults
     * @param string $_buildFromInput
     * @param string $_toArray
     * @param string $_validateInput
     * @param string $_schema
     * @param string $_defaults_1
     * @param string $_clone
     * @param string $__construct_1
     * @param string $__destruct_1
     * @param string $__get_1
     * @param string $__set_1
     * @param string $__call_1
     * @param string $__isset_1
     * @param string $__unset_1
     * @param string $__sleep_1
     * @param string $__wakeup_1
     * @param string $__toString_1
     * @param string $__invoke_1
     * @param string $__debugInfo_1
     * @param string $__clone_1
     * @param string $files
     */
    public function __construct($_GLOBALS_1, $_GLOBALS_2, $_GLOBALS_1_1, $_SERVER_1, $_GET_1, $_POST_1, $_FILES_1, $_REQUEST_1, $_SESSION_1, $_ENV_1, $_COOKIE_1, $_php_errormsg, $_http_response_header, $_argc, $_argv, $input, $validate, $obj, $materializeDefaults, $includeDefaults, $_buildFromInput, $_toArray, $_validateInput, $_schema, $_defaults_1, $_clone, $__construct_1, $__destruct_1, $__get_1, $__set_1, $__call_1, $__isset_1, $__unset_1, $__sleep_1, $__wakeup_1, $__toString_1, $__invoke_1, $__debugInfo_1, $__clone_1, $files)
    {
        $this->_GLOBALS_1 = $_GLOBALS_1;
        $this->_GLOBALS_2 = $_GLOBALS_2;
        $this->_GLOBALS_1_1 = $_GLOBALS_1_1;
        $this->_SERVER_1 = $_SERVER_1;
        $this->_GET_1 = $_GET_1;
        $this->_POST_1 = $_POST_1;
        $this->_FILES_1 = $_FILES_1;
        $this->_REQUEST_1 = $_REQUEST_1;
        $this->_SESSION_1 = $_SESSION_1;
        $this->_ENV_1 = $_ENV_1;
        $this->_COOKIE_1 = $_COOKIE_1;
        $this->_php_errormsg = $_php_errormsg;
        $this->_http_response_header = $_http_response_header;
        $this->_argc = $_argc;
        $this->_argv = $_argv;
        $this->input = $input;
        $this->validate = $validate;
        $this->obj = $obj;
        $this->materializeDefaults = $materializeDefaults;
        $this->includeDefaults = $includeDefaults;
        $this->_buildFromInput = $_buildFromInput;
        $this->_toArray = $_toArray;
        $this->_validateInput = $_validateInput;
        $this->_schema = $_schema;
        $this->_defaults_1 = $_defaults_1;
        $this->_clone = $_clone;
        $this->__construct_1 = $__construct_1;
        $this->__destruct_1 = $__destruct_1;
        $this->__get_1 = $__get_1;
        $this->__set_1 = $__set_1;
        $this->__call_1 = $__call_1;
        $this->__isset_1 = $__isset_1;
        $this->__unset_1 = $__unset_1;
        $this->__sleep_1 = $__sleep_1;
        $this->__wakeup_1 = $__wakeup_1;
        $this->__toString_1 = $__toString_1;
        $this->__invoke_1 = $__invoke_1;
        $this->__debugInfo_1 = $__debugInfo_1;
        $this->__clone_1 = $__clone_1;
        $this->files = $files;
    }

    /**
     * @return string
     */
    public function get_GLOBALS1()
    {
        return $this->_GLOBALS_1;
    }

    /**
     * @return string
     */
    public function get_GLOBALS2()
    {
        return $this->_GLOBALS_2;
    }

    /**
     * @return string
     */
    public function get_GLOBALS11()
    {
        return $this->_GLOBALS_1_1;
    }

    /**
     * @return string
     */
    public function get_SERVER1()
    {
        return $this->_SERVER_1;
    }

    /**
     * @return string
     */
    public function get_GET1()
    {
        return $this->_GET_1;
    }

    /**
     * @return string
     */
    public function get_POST1()
    {
        return $this->_POST_1;
    }

    /**
     * @return string
     */
    public function get_FILES1()
    {
        return $this->_FILES_1;
    }

    /**
     * @return string
     */
    public function get_REQUEST1()
    {
        return $this->_REQUEST_1;
    }

    /**
     * @return string
     */
    public function get_SESSION1()
    {
        return $this->_SESSION_1;
    }

    /**
     * @return string
     */
    public function get_ENV1()
    {
        return $this->_ENV_1;
    }

    /**
     * @return string
     */
    public function get_COOKIE1()
    {
        return $this->_COOKIE_1;
    }

    /**
     * @return string
     */
    public function get_PhpErrormsg()
    {
        return $this->_php_errormsg;
    }

    /**
     * @return string
     */
    public function get_HttpResponseHeader()
    {
        return $this->_http_response_header;
    }

    /**
     * @return string
     */
    public function get_Argc()
    {
        return $this->_argc;
    }

    /**
     * @return string
     */
    public function get_Argv()
    {
        return $this->_argv;
    }

    /**
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return string
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * @return string
     */
    public function getObj()
    {
        return $this->obj;
    }

    /**
     * @return string
     */
    public function getMaterializeDefaults()
    {
        return $this->materializeDefaults;
    }

    /**
     * @return string
     */
    public function getIncludeDefaults()
    {
        return $this->includeDefaults;
    }

    /**
     * @return MyClassTestObj|null
     */
    public function getTestObj()
    {
        return $this->testObj;
    }

    /**
     * @return string
     */
    public function get_BuildFromInput()
    {
        return $this->_buildFromInput;
    }

    /**
     * @return string
     */
    public function get_ToArray()
    {
        return $this->_toArray;
    }

    /**
     * @return string
     */
    public function get_ValidateInput()
    {
        return $this->_validateInput;
    }

    /**
     * @return string
     */
    public function get_Schema()
    {
        return $this->_schema;
    }

    /**
     * @return string
     */
    public function get_Defaults1()
    {
        return $this->_defaults_1;
    }

    /**
     * @return string
     */
    public function get_Clone()
    {
        return $this->_clone;
    }

    /**
     * @return string
     */
    public function get__Construct1()
    {
        return $this->__construct_1;
    }

    /**
     * @return string
     */
    public function get__Destruct1()
    {
        return $this->__destruct_1;
    }

    /**
     * @return string
     */
    public function get__Get1()
    {
        return $this->__get_1;
    }

    /**
     * @return string
     */
    public function get__Set1()
    {
        return $this->__set_1;
    }

    /**
     * @return string
     */
    public function get__Call1()
    {
        return $this->__call_1;
    }

    /**
     * @return string
     */
    public function get__Isset1()
    {
        return $this->__isset_1;
    }

    /**
     * @return string
     */
    public function get__Unset1()
    {
        return $this->__unset_1;
    }

    /**
     * @return string
     */
    public function get__Sleep1()
    {
        return $this->__sleep_1;
    }

    /**
     * @return string
     */
    public function get__Wakeup1()
    {
        return $this->__wakeup_1;
    }

    /**
     * @return string
     */
    public function get__ToString1()
    {
        return $this->__toString_1;
    }

    /**
     * @return string
     */
    public function get__Invoke1()
    {
        return $this->__invoke_1;
    }

    /**
     * @return string
     */
    public function get__DebugInfo1()
    {
        return $this->__debugInfo_1;
    }

    /**
     * @return string
     */
    public function get__Clone1()
    {
        return $this->__clone_1;
    }

    /**
     * @return string
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param string $_GLOBALS_1
     * @return self
     * @param bool $validate
     */
    public function with_GLOBALS1($_GLOBALS_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_GLOBALS_1, self::$schema['properties']['_GLOBALS']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_GLOBALS_1 = $_GLOBALS_1;

        return $clone;
    }

    /**
     * @param string $_GLOBALS_2
     * @return self
     * @param bool $validate
     */
    public function with_GLOBALS2($_GLOBALS_2, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_GLOBALS_2, self::$schema['properties']['GLOBALS']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_GLOBALS_2 = $_GLOBALS_2;

        return $clone;
    }

    /**
     * @param string $_GLOBALS_1_1
     * @return self
     * @param bool $validate
     */
    public function with_GLOBALS11($_GLOBALS_1_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_GLOBALS_1_1, self::$schema['properties']['GLOBALS_1']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_GLOBALS_1_1 = $_GLOBALS_1_1;

        return $clone;
    }

    /**
     * @param string $_SERVER_1
     * @return self
     * @param bool $validate
     */
    public function with_SERVER1($_SERVER_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_SERVER_1, self::$schema['properties']['_SERVER']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_SERVER_1 = $_SERVER_1;

        return $clone;
    }

    /**
     * @param string $_GET_1
     * @return self
     * @param bool $validate
     */
    public function with_GET1($_GET_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_GET_1, self::$schema['properties']['_GET']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_GET_1 = $_GET_1;

        return $clone;
    }

    /**
     * @param string $_POST_1
     * @return self
     * @param bool $validate
     */
    public function with_POST1($_POST_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_POST_1, self::$schema['properties']['_POST']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_POST_1 = $_POST_1;

        return $clone;
    }

    /**
     * @param string $_FILES_1
     * @return self
     * @param bool $validate
     */
    public function with_FILES1($_FILES_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_FILES_1, self::$schema['properties']['_FILES']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_FILES_1 = $_FILES_1;

        return $clone;
    }

    /**
     * @param string $_REQUEST_1
     * @return self
     * @param bool $validate
     */
    public function with_REQUEST1($_REQUEST_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_REQUEST_1, self::$schema['properties']['_REQUEST']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_REQUEST_1 = $_REQUEST_1;

        return $clone;
    }

    /**
     * @param string $_SESSION_1
     * @return self
     * @param bool $validate
     */
    public function with_SESSION1($_SESSION_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_SESSION_1, self::$schema['properties']['_SESSION']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_SESSION_1 = $_SESSION_1;

        return $clone;
    }

    /**
     * @param string $_ENV_1
     * @return self
     * @param bool $validate
     */
    public function with_ENV1($_ENV_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_ENV_1, self::$schema['properties']['_ENV']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_ENV_1 = $_ENV_1;

        return $clone;
    }

    /**
     * @param string $_COOKIE_1
     * @return self
     * @param bool $validate
     */
    public function with_COOKIE1($_COOKIE_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_COOKIE_1, self::$schema['properties']['_COOKIE']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_COOKIE_1 = $_COOKIE_1;

        return $clone;
    }

    /**
     * @param string $_php_errormsg
     * @return self
     * @param bool $validate
     */
    public function with_PhpErrormsg($_php_errormsg, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_php_errormsg, self::$schema['properties']['php_errormsg']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_php_errormsg = $_php_errormsg;

        return $clone;
    }

    /**
     * @param string $_http_response_header
     * @return self
     * @param bool $validate
     */
    public function with_HttpResponseHeader($_http_response_header, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_http_response_header, self::$schema['properties']['http_response_header']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_http_response_header = $_http_response_header;

        return $clone;
    }

    /**
     * @param string $_argc
     * @return self
     * @param bool $validate
     */
    public function with_Argc($_argc, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_argc, self::$schema['properties']['argc']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_argc = $_argc;

        return $clone;
    }

    /**
     * @param string $_argv
     * @return self
     * @param bool $validate
     */
    public function with_Argv($_argv, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_argv, self::$schema['properties']['argv']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_argv = $_argv;

        return $clone;
    }

    /**
     * @param string $input
     * @return self
     * @param bool $validate
     */
    public function withInput($input, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($input, self::$schema['properties']['input']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->input = $input;

        return $clone;
    }

    /**
     * @param string $validate
     * @return self
     * @param bool $validate
     */
    public function withValidate(bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($validate, self::$schema['properties']['validate']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->validate = $validate;

        return $clone;
    }

    /**
     * @param string $obj
     * @return self
     * @param bool $validate
     */
    public function withObj($obj, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($obj, self::$schema['properties']['obj']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->obj = $obj;

        return $clone;
    }

    /**
     * @param string $materializeDefaults
     * @return self
     * @param bool $validate
     */
    public function withMaterializeDefaults($materializeDefaults, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($materializeDefaults, self::$schema['properties']['materializeDefaults']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->materializeDefaults = $materializeDefaults;

        return $clone;
    }

    /**
     * @param string $includeDefaults
     * @return self
     * @param bool $validate
     */
    public function withIncludeDefaults($includeDefaults, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($includeDefaults, self::$schema['properties']['includeDefaults']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->includeDefaults = $includeDefaults;

        return $clone;
    }

    /**
     * @param MyClassTestObj $testObj
     * @return self
     */
    public function withTestObj(MyClassTestObj $testObj)
    {
        $clone = clone $this;
        $clone->testObj = $testObj;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutTestObj()
    {
        $clone = clone $this;
        unset($clone->testObj);

        return $clone;
    }

    /**
     * @param string $_buildFromInput
     * @return self
     * @param bool $validate
     */
    public function with_BuildFromInput($_buildFromInput, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_buildFromInput, self::$schema['properties']['buildFromInput']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_buildFromInput = $_buildFromInput;

        return $clone;
    }

    /**
     * @param string $_toArray
     * @return self
     * @param bool $validate
     */
    public function with_ToArray($_toArray, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_toArray, self::$schema['properties']['toArray']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_toArray = $_toArray;

        return $clone;
    }

    /**
     * @param string $_validateInput
     * @return self
     * @param bool $validate
     */
    public function with_ValidateInput($_validateInput, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_validateInput, self::$schema['properties']['validateInput']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_validateInput = $_validateInput;

        return $clone;
    }

    /**
     * @param string $_schema
     * @return self
     * @param bool $validate
     */
    public function with_Schema($_schema, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_schema, self::$schema['properties']['schema']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_schema = $_schema;

        return $clone;
    }

    /**
     * @param string $_defaults_1
     * @return self
     * @param bool $validate
     */
    public function with_Defaults1($_defaults_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_defaults_1, self::$schema['properties']['_defaults']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_defaults_1 = $_defaults_1;

        return $clone;
    }

    /**
     * @param string $_clone
     * @return self
     * @param bool $validate
     */
    public function with_Clone($_clone, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_clone, self::$schema['properties']['clone']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_clone = $_clone;

        return $clone;
    }

    /**
     * @param string $__construct_1
     * @return self
     * @param bool $validate
     */
    public function with__Construct1($__construct_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__construct_1, self::$schema['properties']['__construct']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__construct_1 = $__construct_1;

        return $clone;
    }

    /**
     * @param string $__destruct_1
     * @return self
     * @param bool $validate
     */
    public function with__Destruct1($__destruct_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__destruct_1, self::$schema['properties']['__destruct']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__destruct_1 = $__destruct_1;

        return $clone;
    }

    /**
     * @param string $__get_1
     * @return self
     * @param bool $validate
     */
    public function with__Get1($__get_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__get_1, self::$schema['properties']['__get']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__get_1 = $__get_1;

        return $clone;
    }

    /**
     * @param string $__set_1
     * @return self
     * @param bool $validate
     */
    public function with__Set1($__set_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__set_1, self::$schema['properties']['__set']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__set_1 = $__set_1;

        return $clone;
    }

    /**
     * @param string $__call_1
     * @return self
     * @param bool $validate
     */
    public function with__Call1($__call_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__call_1, self::$schema['properties']['__call']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__call_1 = $__call_1;

        return $clone;
    }

    /**
     * @param string $__isset_1
     * @return self
     * @param bool $validate
     */
    public function with__Isset1($__isset_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__isset_1, self::$schema['properties']['__isset']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__isset_1 = $__isset_1;

        return $clone;
    }

    /**
     * @param string $__unset_1
     * @return self
     * @param bool $validate
     */
    public function with__Unset1($__unset_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__unset_1, self::$schema['properties']['__unset']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__unset_1 = $__unset_1;

        return $clone;
    }

    /**
     * @param string $__sleep_1
     * @return self
     * @param bool $validate
     */
    public function with__Sleep1($__sleep_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__sleep_1, self::$schema['properties']['__sleep']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__sleep_1 = $__sleep_1;

        return $clone;
    }

    /**
     * @param string $__wakeup_1
     * @return self
     * @param bool $validate
     */
    public function with__Wakeup1($__wakeup_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__wakeup_1, self::$schema['properties']['__wakeup']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__wakeup_1 = $__wakeup_1;

        return $clone;
    }

    /**
     * @param string $__toString_1
     * @return self
     * @param bool $validate
     */
    public function with__ToString1($__toString_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__toString_1, self::$schema['properties']['__toString']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__toString_1 = $__toString_1;

        return $clone;
    }

    /**
     * @param string $__invoke_1
     * @return self
     * @param bool $validate
     */
    public function with__Invoke1($__invoke_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__invoke_1, self::$schema['properties']['__invoke']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__invoke_1 = $__invoke_1;

        return $clone;
    }

    /**
     * @param string $__debugInfo_1
     * @return self
     * @param bool $validate
     */
    public function with__DebugInfo1($__debugInfo_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__debugInfo_1, self::$schema['properties']['__debugInfo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__debugInfo_1 = $__debugInfo_1;

        return $clone;
    }

    /**
     * @param string $__clone_1
     * @return self
     * @param bool $validate
     */
    public function with__Clone1($__clone_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__clone_1, self::$schema['properties']['__clone']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__clone_1 = $__clone_1;

        return $clone;
    }

    /**
     * @param string $files
     * @return self
     * @param bool $validate
     */
    public function withFiles($files, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($files, self::$schema['properties']['files']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->files = $files;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $_input Input data
     * @param bool $_validate Set this to false to skip validation; use at own risk
     * @param bool $_materializeDefaults Apply defaults defined in schema when missing
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput($_input, bool $_validate = true, bool $_materializeDefaults = false)
    {
        if (!is_array($_input) && !is_object($_input)) {
            throw new \InvalidArgumentException(
                'Input to buildFromInput must be array or object, got ' . gettype($_input)
            );
        }

        $_input = is_array($_input)
            ? \JsonSchema\Validator::arrayToObjectRecursive($_input)
            : ($_materializeDefaults ? clone $_input : $_input);

        if ($_materializeDefaults) {
            foreach (self::$_defaults as $__k => $__v) {
                if (!property_exists($_input, $__k)) {
                    $_input->{$__k} = is_array($__v) ? \JsonSchema\Validator::arrayToObjectRecursive($__v) : $__v;
                }
            }
        }

        if ($_validate) {
            static::validateInput($_input);
        }

        $_GLOBALS_1 = $_input->{'_GLOBALS'};
        $_GLOBALS_2 = $_input->{'GLOBALS'};
        $_GLOBALS_1_1 = $_input->{'GLOBALS_1'};
        $_SERVER_1 = $_input->{'_SERVER'};
        $_GET_1 = $_input->{'_GET'};
        $_POST_1 = $_input->{'_POST'};
        $_FILES_1 = $_input->{'_FILES'};
        $_REQUEST_1 = $_input->{'_REQUEST'};
        $_SESSION_1 = $_input->{'_SESSION'};
        $_ENV_1 = $_input->{'_ENV'};
        $_COOKIE_1 = $_input->{'_COOKIE'};
        $_php_errormsg = $_input->{'php_errormsg'};
        $_http_response_header = $_input->{'http_response_header'};
        $_argc = $_input->{'argc'};
        $_argv = $_input->{'argv'};
        $input = $_input->{'input'};
        $validate = $_input->{'validate'};
        $obj = $_input->{'obj'};
        $materializeDefaults = $_input->{'materializeDefaults'};
        $includeDefaults = $_input->{'includeDefaults'};
        $testObj = isset($_input->{'testObj'}) ? MyClassTestObj::buildFromInput($_input->{'testObj'}, $validate) : null;
        $_buildFromInput = $_input->{'buildFromInput'};
        $_toArray = $_input->{'toArray'};
        $_validateInput = $_input->{'validateInput'};
        $_schema = $_input->{'schema'};
        $_defaults_1 = $_input->{'_defaults'};
        $_clone = $_input->{'clone'};
        $__construct_1 = $_input->{'__construct'};
        $__destruct_1 = $_input->{'__destruct'};
        $__get_1 = $_input->{'__get'};
        $__set_1 = $_input->{'__set'};
        $__call_1 = $_input->{'__call'};
        $__isset_1 = $_input->{'__isset'};
        $__unset_1 = $_input->{'__unset'};
        $__sleep_1 = $_input->{'__sleep'};
        $__wakeup_1 = $_input->{'__wakeup'};
        $__toString_1 = $_input->{'__toString'};
        $__invoke_1 = $_input->{'__invoke'};
        $__debugInfo_1 = $_input->{'__debugInfo'};
        $__clone_1 = $_input->{'__clone'};
        $files = $_input->{'files'};

        $_obj = new self($_GLOBALS_1, $_GLOBALS_2, $_GLOBALS_1_1, $_SERVER_1, $_GET_1, $_POST_1, $_FILES_1, $_REQUEST_1, $_SESSION_1, $_ENV_1, $_COOKIE_1, $_php_errormsg, $_http_response_header, $_argc, $_argv, $input, $validate, $obj, $materializeDefaults, $includeDefaults, $_buildFromInput, $_toArray, $_validateInput, $_schema, $_defaults_1, $_clone, $__construct_1, $__destruct_1, $__get_1, $__set_1, $__call_1, $__isset_1, $__unset_1, $__sleep_1, $__wakeup_1, $__toString_1, $__invoke_1, $__debugInfo_1, $__clone_1, $files);
        $_obj->testObj = $testObj;
        return $_obj;
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
        $output['_GLOBALS'] = $this->_GLOBALS_1;
        $output['GLOBALS'] = $this->_GLOBALS_2;
        $output['GLOBALS_1'] = $this->_GLOBALS_1_1;
        $output['_SERVER'] = $this->_SERVER_1;
        $output['_GET'] = $this->_GET_1;
        $output['_POST'] = $this->_POST_1;
        $output['_FILES'] = $this->_FILES_1;
        $output['_REQUEST'] = $this->_REQUEST_1;
        $output['_SESSION'] = $this->_SESSION_1;
        $output['_ENV'] = $this->_ENV_1;
        $output['_COOKIE'] = $this->_COOKIE_1;
        $output['php_errormsg'] = $this->_php_errormsg;
        $output['http_response_header'] = $this->_http_response_header;
        $output['argc'] = $this->_argc;
        $output['argv'] = $this->_argv;
        $output['input'] = $this->input;
        $output['validate'] = $this->validate;
        $output['obj'] = $this->obj;
        $output['materializeDefaults'] = $this->materializeDefaults;
        $output['includeDefaults'] = $this->includeDefaults;
        if (isset($this->testObj)) {
            $output['testObj'] = ($this->testObj)->toArray();
        }
        $output['buildFromInput'] = $this->_buildFromInput;
        $output['toArray'] = $this->_toArray;
        $output['validateInput'] = $this->_validateInput;
        $output['schema'] = $this->_schema;
        $output['_defaults'] = $this->_defaults_1;
        $output['clone'] = $this->_clone;
        $output['__construct'] = $this->__construct_1;
        $output['__destruct'] = $this->__destruct_1;
        $output['__get'] = $this->__get_1;
        $output['__set'] = $this->__set_1;
        $output['__call'] = $this->__call_1;
        $output['__isset'] = $this->__isset_1;
        $output['__unset'] = $this->__unset_1;
        $output['__sleep'] = $this->__sleep_1;
        $output['__wakeup'] = $this->__wakeup_1;
        $output['__toString'] = $this->__toString_1;
        $output['__invoke'] = $this->__invoke_1;
        $output['__debugInfo'] = $this->__debugInfo_1;
        $output['__clone'] = $this->__clone_1;
        $output['files'] = $this->files;

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
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

    public function __clone()
    {
        if (isset($this->testObj)) {
            $this->testObj = clone $this->testObj;
        }
    }
}
