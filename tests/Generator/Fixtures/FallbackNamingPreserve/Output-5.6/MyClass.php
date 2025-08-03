<?php

namespace Ns\FallbackNamingPreserve_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
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
            'obj',
            'includeDefaults',
            'fromInput',
            'toArray',
            'toStdClass',
            'validateInput',
            '_schema',
            'schema',
            '_defaults',
            'defaults',
            '_providedOptionals',
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
            'this',
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
            'materializeDefaults' => [
                'type' => [
                    'string',
                    'null',
                ],
            ],
            'obj' => [
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
            'fromInput' => [
                'type' => 'string',
            ],
            'toArray' => [
                'type' => 'string',
            ],
            'toStdClass' => [
                'type' => 'string',
            ],
            'validateInput' => [
                'type' => 'string',
            ],
            '_schema' => [
                'type' => 'string',
            ],
            'schema' => [
                'type' => 'string',
            ],
            '_defaults' => [
                'type' => 'string',
                'default' => 'foo',
            ],
            'defaults' => [
                'type' => 'string',
                'default' => 'foo',
            ],
            '_providedOptionals' => [
                'type' => 'string',
            ],
            '__providedOptionals' => [
                'type' => 'string',
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
            'this' => [
                'type' => 'string',
            ],
            'ensureArgs1' => [
                'oneOf' => [
                    [
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                                'enum' => [
                                    'invoice',
                                ],
                                'default' => 'invoice',
                            ],
                        ],
                    ],
                    [
                        'required' => [
                            'type',
                            'accountNumber',
                        ],
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                                'default' => 'debit',
                            ],
                            'accountNumber' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                    [
                        'type' => 'string',
                    ],
                ],
            ],
            'ensureArgs2' => [
                'required' => [
                    'city',
                    'street',
                ],
                'properties' => [
                    'city' => [
                        'type' => 'string',
                        'maxLength' => 32,
                    ],
                    'street' => [
                        'type' => 'string',
                        'default' => '-',
                    ],
                ],
            ],
            'ensureArgs3' => [
                'type' => 'array',
                'items' => [
                    'properties' => [
                        'name' => [
                            'type' => 'string',
                            'default' => '-',
                        ],
                    ],
                ],
            ],
        ],
    ];

    /**
     * Default values from the schema
     *
     * @var array
     */
    private static $_defaults = [
        '_defaults' => [
            'default' => 'foo',
        ],
        'defaults' => [
            'default' => 'foo',
        ],
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private $_providedOptionals = [];

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
    private $GLOBALS_1;

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
     * @var string|null
     */
    private $validate = null;

    /**
     * @var string|null
     */
    private $materializeDefaults = null;

    /**
     * @var string
     */
    private $obj;

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
    private $_fromInput;

    /**
     * @var string
     */
    private $_toArray;

    /**
     * @var string
     */
    private $_toStdClass;

    /**
     * @var string
     */
    private $_validateInput;

    /**
     * @var string
     */
    private $_schema_1;

    /**
     * @var string
     */
    private $_schema_2;

    /**
     * @var string
     */
    private $_defaults_1;

    /**
     * @var string
     */
    private $_defaults_2;

    /**
     * @var string
     */
    private $_providedOptionals_1;

    /**
     * @var string|null
     */
    private $__providedOptionals = null;

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
     * @var string
     */
    private $_this;

    /**
     * @var MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string|null
     */
    private $ensureArgs1 = null;

    /**
     * @var MyClassEnsureArgs2|null
     */
    private $ensureArgs2 = null;

    /**
     * @var MyClassEnsureArgs3Item[]|null
     */
    private $ensureArgs3 = null;

    /**
     * @param string $_GLOBALS_1
     * @param string $_GLOBALS_2
     * @param string $GLOBALS_1
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
     * @param string $obj
     * @param string $includeDefaults
     * @param string $_fromInput
     * @param string $_toArray
     * @param string $_toStdClass
     * @param string $_validateInput
     * @param string $_schema_1
     * @param string $_schema_2
     * @param string $_defaults_1
     * @param string $_defaults_2
     * @param string $_providedOptionals_1
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
     * @param string $_this
     */
    public function __construct($_GLOBALS_1, $_GLOBALS_2, $GLOBALS_1, $_SERVER_1, $_GET_1, $_POST_1, $_FILES_1, $_REQUEST_1, $_SESSION_1, $_ENV_1, $_COOKIE_1, $_php_errormsg, $_http_response_header, $_argc, $_argv, $input, $obj, $includeDefaults, $_fromInput, $_toArray, $_toStdClass, $_validateInput, $_schema_1, $_schema_2, $_defaults_1, $_defaults_2, $_providedOptionals_1, $_clone, $__construct_1, $__destruct_1, $__get_1, $__set_1, $__call_1, $__isset_1, $__unset_1, $__sleep_1, $__wakeup_1, $__toString_1, $__invoke_1, $__debugInfo_1, $__clone_1, $files, $_this)
    {
        $this->_GLOBALS_1 = $_GLOBALS_1;
        $this->_GLOBALS_2 = $_GLOBALS_2;
        $this->GLOBALS_1 = $GLOBALS_1;
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
        $this->obj = $obj;
        $this->includeDefaults = $includeDefaults;
        $this->_fromInput = $_fromInput;
        $this->_toArray = $_toArray;
        $this->_toStdClass = $_toStdClass;
        $this->_validateInput = $_validateInput;
        $this->_schema_1 = $_schema_1;
        $this->_schema_2 = $_schema_2;
        $this->_defaults_1 = $_defaults_1;
        $this->_defaults_2 = $_defaults_2;
        $this->_providedOptionals_1 = $_providedOptionals_1;
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
        $this->_this = $_this;
    }

    /**
     * @return string
     */
    public function get_GLOBALS1()
    {
        return $this->_GLOBALS_1;
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
            $validator->validate($_GLOBALS_1, self::$_schema['properties']['_GLOBALS']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_GLOBALS_1 = $_GLOBALS_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_GLOBALS2()
    {
        return $this->_GLOBALS_2;
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
            $validator->validate($_GLOBALS_2, self::$_schema['properties']['GLOBALS']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_GLOBALS_2 = $_GLOBALS_2;

        return $clone;
    }

    /**
     * @return string
     */
    public function getGLOBALS1()
    {
        return $this->GLOBALS_1;
    }

    /**
     * @param string $GLOBALS_1
     * @return self
     * @param bool $validate
     */
    public function withGLOBALS1($GLOBALS_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($GLOBALS_1, self::$_schema['properties']['GLOBALS_1']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->GLOBALS_1 = $GLOBALS_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_SERVER1()
    {
        return $this->_SERVER_1;
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
            $validator->validate($_SERVER_1, self::$_schema['properties']['_SERVER']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_SERVER_1 = $_SERVER_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_GET1()
    {
        return $this->_GET_1;
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
            $validator->validate($_GET_1, self::$_schema['properties']['_GET']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_GET_1 = $_GET_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_POST1()
    {
        return $this->_POST_1;
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
            $validator->validate($_POST_1, self::$_schema['properties']['_POST']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_POST_1 = $_POST_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_FILES1()
    {
        return $this->_FILES_1;
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
            $validator->validate($_FILES_1, self::$_schema['properties']['_FILES']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_FILES_1 = $_FILES_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_REQUEST1()
    {
        return $this->_REQUEST_1;
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
            $validator->validate($_REQUEST_1, self::$_schema['properties']['_REQUEST']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_REQUEST_1 = $_REQUEST_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_SESSION1()
    {
        return $this->_SESSION_1;
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
            $validator->validate($_SESSION_1, self::$_schema['properties']['_SESSION']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_SESSION_1 = $_SESSION_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_ENV1()
    {
        return $this->_ENV_1;
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
            $validator->validate($_ENV_1, self::$_schema['properties']['_ENV']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_ENV_1 = $_ENV_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_COOKIE1()
    {
        return $this->_COOKIE_1;
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
            $validator->validate($_COOKIE_1, self::$_schema['properties']['_COOKIE']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_COOKIE_1 = $_COOKIE_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_PhpErrormsg()
    {
        return $this->_php_errormsg;
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
            $validator->validate($_php_errormsg, self::$_schema['properties']['php_errormsg']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_php_errormsg = $_php_errormsg;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_HttpResponseHeader()
    {
        return $this->_http_response_header;
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
            $validator->validate($_http_response_header, self::$_schema['properties']['http_response_header']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_http_response_header = $_http_response_header;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_Argc()
    {
        return $this->_argc;
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
            $validator->validate($_argc, self::$_schema['properties']['argc']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_argc = $_argc;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_Argv()
    {
        return $this->_argv;
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
            $validator->validate($_argv, self::$_schema['properties']['argv']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_argv = $_argv;

        return $clone;
    }

    /**
     * @return string
     */
    public function getInput()
    {
        return $this->input;
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
            $validator->validate($input, self::$_schema['properties']['input']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->input = $input;

        return $clone;
    }

    /**
     * @return string|null
     */
    public function getValidate()
    {
        return $this->validate;
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
            $validator->validate($validate, self::$_schema['properties']['validate']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->validate = $validate;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutValidate()
    {
        $clone = clone $this;
        unset($clone->validate);

        return $clone;
    }

    /**
     * @return string|null
     */
    public function getMaterializeDefaults()
    {
        return $this->materializeDefaults;
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
            $validator->validate($materializeDefaults, self::$_schema['properties']['materializeDefaults']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->materializeDefaults = $materializeDefaults;
        $clone->_providedOptionals['materializeDefaults'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutMaterializeDefaults()
    {
        $clone = clone $this;
        unset($clone->materializeDefaults);
        unset($clone->_providedOptionals['materializeDefaults']);

        return $clone;
    }

    /**
     * @return string
     */
    public function getObj()
    {
        return $this->obj;
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
            $validator->validate($obj, self::$_schema['properties']['obj']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->obj = $obj;

        return $clone;
    }

    /**
     * @return string
     */
    public function getIncludeDefaults()
    {
        return $this->includeDefaults;
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
            $validator->validate($includeDefaults, self::$_schema['properties']['includeDefaults']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->includeDefaults = $includeDefaults;

        return $clone;
    }

    /**
     * @return MyClassTestObj|null
     */
    public function getTestObj()
    {
        return $this->testObj;
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
     * @return string
     */
    public function get_FromInput()
    {
        return $this->_fromInput;
    }

    /**
     * @param string $_fromInput
     * @return self
     * @param bool $validate
     */
    public function with_FromInput($_fromInput, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_fromInput, self::$_schema['properties']['fromInput']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_fromInput = $_fromInput;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_ToArray()
    {
        return $this->_toArray;
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
            $validator->validate($_toArray, self::$_schema['properties']['toArray']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_toArray = $_toArray;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_ToStdClass()
    {
        return $this->_toStdClass;
    }

    /**
     * @param string $_toStdClass
     * @return self
     * @param bool $validate
     */
    public function with_ToStdClass($_toStdClass, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_toStdClass, self::$_schema['properties']['toStdClass']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_toStdClass = $_toStdClass;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_ValidateInput()
    {
        return $this->_validateInput;
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
            $validator->validate($_validateInput, self::$_schema['properties']['validateInput']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_validateInput = $_validateInput;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_Schema1()
    {
        return $this->_schema_1;
    }

    /**
     * @param string $_schema_1
     * @return self
     * @param bool $validate
     */
    public function with_Schema1($_schema_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_schema_1, self::$_schema['properties']['_schema']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_schema_1 = $_schema_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_Schema2()
    {
        return $this->_schema_2;
    }

    /**
     * @param string $_schema_2
     * @return self
     * @param bool $validate
     */
    public function with_Schema2($_schema_2, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_schema_2, self::$_schema['properties']['schema']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_schema_2 = $_schema_2;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_Defaults1()
    {
        return $this->_defaults_1;
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
            $validator->validate($_defaults_1, self::$_schema['properties']['_defaults']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_defaults_1 = $_defaults_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_Defaults2()
    {
        return $this->_defaults_2;
    }

    /**
     * @param string $_defaults_2
     * @return self
     * @param bool $validate
     */
    public function with_Defaults2($_defaults_2, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_defaults_2, self::$_schema['properties']['defaults']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_defaults_2 = $_defaults_2;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_ProvidedOptionals1()
    {
        return $this->_providedOptionals_1;
    }

    /**
     * @param string $_providedOptionals_1
     * @return self
     * @param bool $validate
     */
    public function with_ProvidedOptionals1($_providedOptionals_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_providedOptionals_1, self::$_schema['properties']['_providedOptionals']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_providedOptionals_1 = $_providedOptionals_1;

        return $clone;
    }

    /**
     * @return string|null
     */
    public function get__ProvidedOptionals()
    {
        return $this->__providedOptionals;
    }

    /**
     * @param string $__providedOptionals
     * @return self
     * @param bool $validate
     */
    public function with__ProvidedOptionals($__providedOptionals, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__providedOptionals, self::$_schema['properties']['__providedOptionals']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__providedOptionals = $__providedOptionals;

        return $clone;
    }

    /**
     * @return self
     */
    public function without__ProvidedOptionals()
    {
        $clone = clone $this;
        unset($clone->__providedOptionals);

        return $clone;
    }

    /**
     * @return string
     */
    public function get_Clone()
    {
        return $this->_clone;
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
            $validator->validate($_clone, self::$_schema['properties']['clone']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_clone = $_clone;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__Construct1()
    {
        return $this->__construct_1;
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
            $validator->validate($__construct_1, self::$_schema['properties']['__construct']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__construct_1 = $__construct_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__Destruct1()
    {
        return $this->__destruct_1;
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
            $validator->validate($__destruct_1, self::$_schema['properties']['__destruct']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__destruct_1 = $__destruct_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__Get1()
    {
        return $this->__get_1;
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
            $validator->validate($__get_1, self::$_schema['properties']['__get']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__get_1 = $__get_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__Set1()
    {
        return $this->__set_1;
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
            $validator->validate($__set_1, self::$_schema['properties']['__set']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__set_1 = $__set_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__Call1()
    {
        return $this->__call_1;
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
            $validator->validate($__call_1, self::$_schema['properties']['__call']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__call_1 = $__call_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__Isset1()
    {
        return $this->__isset_1;
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
            $validator->validate($__isset_1, self::$_schema['properties']['__isset']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__isset_1 = $__isset_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__Unset1()
    {
        return $this->__unset_1;
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
            $validator->validate($__unset_1, self::$_schema['properties']['__unset']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__unset_1 = $__unset_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__Sleep1()
    {
        return $this->__sleep_1;
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
            $validator->validate($__sleep_1, self::$_schema['properties']['__sleep']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__sleep_1 = $__sleep_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__Wakeup1()
    {
        return $this->__wakeup_1;
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
            $validator->validate($__wakeup_1, self::$_schema['properties']['__wakeup']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__wakeup_1 = $__wakeup_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__ToString1()
    {
        return $this->__toString_1;
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
            $validator->validate($__toString_1, self::$_schema['properties']['__toString']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__toString_1 = $__toString_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__Invoke1()
    {
        return $this->__invoke_1;
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
            $validator->validate($__invoke_1, self::$_schema['properties']['__invoke']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__invoke_1 = $__invoke_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__DebugInfo1()
    {
        return $this->__debugInfo_1;
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
            $validator->validate($__debugInfo_1, self::$_schema['properties']['__debugInfo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__debugInfo_1 = $__debugInfo_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__Clone1()
    {
        return $this->__clone_1;
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
            $validator->validate($__clone_1, self::$_schema['properties']['__clone']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__clone_1 = $__clone_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getFiles()
    {
        return $this->files;
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
            $validator->validate($files, self::$_schema['properties']['files']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->files = $files;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_This()
    {
        return $this->_this;
    }

    /**
     * @param string $_this
     * @return self
     * @param bool $validate
     */
    public function with_This($_this, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_this, self::$_schema['properties']['this']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_this = $_this;

        return $clone;
    }

    /**
     * @return MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string|null
     */
    public function getEnsureArgs1()
    {
        return $this->ensureArgs1;
    }

    /**
     * @param MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string $ensureArgs1
     * @return self
     */
    public function withEnsureArgs1($ensureArgs1)
    {
        $clone = clone $this;
        $clone->ensureArgs1 = $ensureArgs1;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutEnsureArgs1()
    {
        $clone = clone $this;
        unset($clone->ensureArgs1);

        return $clone;
    }

    /**
     * @return MyClassEnsureArgs2|null
     */
    public function getEnsureArgs2()
    {
        return $this->ensureArgs2;
    }

    /**
     * @param MyClassEnsureArgs2 $ensureArgs2
     * @return self
     */
    public function withEnsureArgs2(MyClassEnsureArgs2 $ensureArgs2)
    {
        $clone = clone $this;
        $clone->ensureArgs2 = $ensureArgs2;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutEnsureArgs2()
    {
        $clone = clone $this;
        unset($clone->ensureArgs2);

        return $clone;
    }

    /**
     * @return MyClassEnsureArgs3Item[]|null
     */
    public function getEnsureArgs3()
    {
        return $this->ensureArgs3;
    }

    /**
     * @param MyClassEnsureArgs3Item[] $ensureArgs3
     * @return self
     * @param bool $validate
     */
    public function withEnsureArgs3(array $ensureArgs3, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($ensureArgs3, self::$_schema['properties']['ensureArgs3']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->ensureArgs3 = $ensureArgs3;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutEnsureArgs3()
    {
        $clone = clone $this;
        unset($clone->ensureArgs3);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @param bool $materializeDefaults Apply defaults defined in schema when missing
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, bool $validate = true, bool $materializeDefaults = false)
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to fromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input)
            ? \JsonSchema\Validator::arrayToObjectRecursive($input)
            : ($materializeDefaults ? clone $input : $input);

        if ($materializeDefaults) {
            foreach (self::$_defaults as $__k => $__v) {
                if (!property_exists($input, (string) $__k)) {
                    $input->{$__k} = ($__v['type'] ?? null) === 'object'
                        ? \JsonSchema\Validator::arrayToObjectRecursive($__v['default'])
                        : $__v['default'];
                }
            }
        }

        if ($validate) {
            static::validateInput($input);
        }

        $___providedOptionals = [];
        $_GLOBALS_1 = $input->{'_GLOBALS'};
        $_GLOBALS_2 = $input->{'GLOBALS'};
        $GLOBALS_1 = $input->{'GLOBALS_1'};
        $_SERVER_1 = $input->{'_SERVER'};
        $_GET_1 = $input->{'_GET'};
        $_POST_1 = $input->{'_POST'};
        $_FILES_1 = $input->{'_FILES'};
        $_REQUEST_1 = $input->{'_REQUEST'};
        $_SESSION_1 = $input->{'_SESSION'};
        $_ENV_1 = $input->{'_ENV'};
        $_COOKIE_1 = $input->{'_COOKIE'};
        $_php_errormsg = $input->{'php_errormsg'};
        $_http_response_header = $input->{'http_response_header'};
        $_argc = $input->{'argc'};
        $_argv = $input->{'argv'};
        $_input = $input->{'input'};
        $_validate = isset($input->{'validate'}) ? $input->{'validate'} : null;
        $_materializeDefaults = property_exists($input, 'materializeDefaults') ? ($input->{'materializeDefaults'} !== null ? $input->{'materializeDefaults'} : null) : null;
        if (property_exists($input, 'materializeDefaults')) {
            $___providedOptionals['materializeDefaults'] = true;
        }
        $_obj = $input->{'obj'};
        $includeDefaults = $input->{'includeDefaults'};
        $testObj = isset($input->{'testObj'}) ? MyClassTestObj::fromInput($input->{'testObj'}, $validate, $materializeDefaults) : null;
        $fromInput = $input->{'fromInput'};
        $toArray = $input->{'toArray'};
        $toStdClass = $input->{'toStdClass'};
        $validateInput = $input->{'validateInput'};
        $_schema_1 = $input->{'_schema'};
        $_schema_2 = $input->{'schema'};
        $_defaults_1 = $input->{'_defaults'};
        $_defaults_2 = $input->{'defaults'};
        $_providedOptionals_1 = $input->{'_providedOptionals'};
        $__providedOptionals_1 = isset($input->{'__providedOptionals'}) ? $input->{'__providedOptionals'} : null;
        $clone = $input->{'clone'};
        $__construct = $input->{'__construct'};
        $__destruct = $input->{'__destruct'};
        $__get = $input->{'__get'};
        $__set = $input->{'__set'};
        $__call = $input->{'__call'};
        $__isset = $input->{'__isset'};
        $__unset = $input->{'__unset'};
        $__sleep = $input->{'__sleep'};
        $__wakeup = $input->{'__wakeup'};
        $__toString = $input->{'__toString'};
        $__invoke = $input->{'__invoke'};
        $__debugInfo = $input->{'__debugInfo'};
        $__clone = $input->{'__clone'};
        $files = $input->{'files'};
        $_this = $input->{'this'};
        $ensureArgs1 = isset($input->{'ensureArgs1'}) ? ((is_string($input->{'ensureArgs1'})) ? $input->{'ensureArgs1'} : (((MyClassEnsureArgs1Alternative2::validateInput($input->{'ensureArgs1'}, true)) ? MyClassEnsureArgs1Alternative2::fromInput($input->{'ensureArgs1'}, $validate, $materializeDefaults) : (((MyClassEnsureArgs1Alternative1::validateInput($input->{'ensureArgs1'}, true)) ? MyClassEnsureArgs1Alternative1::fromInput($input->{'ensureArgs1'}, $validate, $materializeDefaults) : (null)))))) : null;
        $ensureArgs2 = isset($input->{'ensureArgs2'}) ? MyClassEnsureArgs2::fromInput($input->{'ensureArgs2'}, $validate, $materializeDefaults) : null;
        $ensureArgs3 = isset($input->{'ensureArgs3'}) ? array_map(function($i) use ($validate, $materializeDefaults) { return MyClassEnsureArgs3Item::fromInput($i, $validate, $materializeDefaults); }, $input->{'ensureArgs3'}) : null;

        $_obj = new self($_GLOBALS_1, $_GLOBALS_2, $GLOBALS_1, $_SERVER_1, $_GET_1, $_POST_1, $_FILES_1, $_REQUEST_1, $_SESSION_1, $_ENV_1, $_COOKIE_1, $_php_errormsg, $_http_response_header, $_argc, $_argv, $_input, $_obj, $includeDefaults, $fromInput, $toArray, $toStdClass, $validateInput, $_schema_1, $_schema_2, $_defaults_1, $_defaults_2, $_providedOptionals_1, $clone, $__construct, $__destruct, $__get, $__set, $__call, $__isset, $__unset, $__sleep, $__wakeup, $__toString, $__invoke, $__debugInfo, $__clone, $files, $_this);
        $_obj->validate = $_validate;
        $_obj->materializeDefaults = $_materializeDefaults;
        $_obj->testObj = $testObj;
        $_obj->__providedOptionals = $__providedOptionals_1;
        $_obj->ensureArgs1 = $ensureArgs1;
        $_obj->ensureArgs2 = $ensureArgs2;
        $_obj->ensureArgs3 = $ensureArgs3;
        $_obj->_providedOptionals = $___providedOptionals;
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
        $output['GLOBALS_1'] = $this->GLOBALS_1;
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
        if (isset($this->validate)) {
            $output['validate'] = $this->validate;
        }
        if (isset($this->materializeDefaults) || array_key_exists('materializeDefaults', $this->_providedOptionals)) {
            $output['materializeDefaults'] = ($this->materializeDefaults !== null) ? ($this->materializeDefaults) : null;
        }
        $output['obj'] = $this->obj;
        $output['includeDefaults'] = $this->includeDefaults;
        if (isset($this->testObj)) {
            $output['testObj'] = ($this->testObj)->toArray($includeDefaults);
        }
        $output['fromInput'] = $this->_fromInput;
        $output['toArray'] = $this->_toArray;
        $output['toStdClass'] = $this->_toStdClass;
        $output['validateInput'] = $this->_validateInput;
        $output['_schema'] = $this->_schema_1;
        $output['schema'] = $this->_schema_2;
        $output['_defaults'] = $this->_defaults_1;
        $output['defaults'] = $this->_defaults_2;
        $output['_providedOptionals'] = $this->_providedOptionals_1;
        if (isset($this->__providedOptionals)) {
            $output['__providedOptionals'] = $this->__providedOptionals;
        }
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
        $output['this'] = $this->_this;
        if (isset($this->ensureArgs1)) {
            if (($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative1) || ($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative2)) {
                $output['ensureArgs1'] = ($this->ensureArgs1)->toArray($includeDefaults);
            } else if ((is_string($this->ensureArgs1))) {
                $output['ensureArgs1'] = $this->ensureArgs1;
            }
        }
        if (isset($this->ensureArgs2)) {
            $output['ensureArgs2'] = ($this->ensureArgs2)->toArray($includeDefaults);
        }
        if (isset($this->ensureArgs3)) {
            $output['ensureArgs3'] = array_map(function(MyClassEnsureArgs3Item $i) { return $i->toArray(); }, $this->ensureArgs3);
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
        $output->{'_GLOBALS'} = $this->_GLOBALS_1;
        $output->{'GLOBALS'} = $this->_GLOBALS_2;
        $output->{'GLOBALS_1'} = $this->GLOBALS_1;
        $output->{'_SERVER'} = $this->_SERVER_1;
        $output->{'_GET'} = $this->_GET_1;
        $output->{'_POST'} = $this->_POST_1;
        $output->{'_FILES'} = $this->_FILES_1;
        $output->{'_REQUEST'} = $this->_REQUEST_1;
        $output->{'_SESSION'} = $this->_SESSION_1;
        $output->{'_ENV'} = $this->_ENV_1;
        $output->{'_COOKIE'} = $this->_COOKIE_1;
        $output->{'php_errormsg'} = $this->_php_errormsg;
        $output->{'http_response_header'} = $this->_http_response_header;
        $output->{'argc'} = $this->_argc;
        $output->{'argv'} = $this->_argv;
        $output->{'input'} = $this->input;
        if (isset($this->validate)) {
            $output->{'validate'} = $this->validate;
        }
        if (isset($this->materializeDefaults) || array_key_exists('materializeDefaults', $this->_providedOptionals)) {
            $output->{'materializeDefaults'} = ($this->materializeDefaults !== null) ? ($this->materializeDefaults) : null;
        }
        $output->{'obj'} = $this->obj;
        $output->{'includeDefaults'} = $this->includeDefaults;
        if (isset($this->testObj)) {
            $output->{'testObj'} = ($this->testObj)->toStdClass($includeDefaults);
        }
        $output->{'fromInput'} = $this->_fromInput;
        $output->{'toArray'} = $this->_toArray;
        $output->{'toStdClass'} = $this->_toStdClass;
        $output->{'validateInput'} = $this->_validateInput;
        $output->{'_schema'} = $this->_schema_1;
        $output->{'schema'} = $this->_schema_2;
        $output->{'_defaults'} = $this->_defaults_1;
        $output->{'defaults'} = $this->_defaults_2;
        $output->{'_providedOptionals'} = $this->_providedOptionals_1;
        if (isset($this->__providedOptionals)) {
            $output->{'__providedOptionals'} = $this->__providedOptionals;
        }
        $output->{'clone'} = $this->_clone;
        $output->{'__construct'} = $this->__construct_1;
        $output->{'__destruct'} = $this->__destruct_1;
        $output->{'__get'} = $this->__get_1;
        $output->{'__set'} = $this->__set_1;
        $output->{'__call'} = $this->__call_1;
        $output->{'__isset'} = $this->__isset_1;
        $output->{'__unset'} = $this->__unset_1;
        $output->{'__sleep'} = $this->__sleep_1;
        $output->{'__wakeup'} = $this->__wakeup_1;
        $output->{'__toString'} = $this->__toString_1;
        $output->{'__invoke'} = $this->__invoke_1;
        $output->{'__debugInfo'} = $this->__debugInfo_1;
        $output->{'__clone'} = $this->__clone_1;
        $output->{'files'} = $this->files;
        $output->{'this'} = $this->_this;
        if (isset($this->ensureArgs1)) {
            if (($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative1) || ($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative2)) {
            $output->{'ensureArgs1'} = ($this->ensureArgs1)->toStdClass($includeDefaults);
            } else if ((is_string($this->ensureArgs1))) {
            $output->{'ensureArgs1'} = $this->ensureArgs1;
            }
        }
        if (isset($this->ensureArgs2)) {
            $output->{'ensureArgs2'} = ($this->ensureArgs2)->toStdClass($includeDefaults);
        }
        if (isset($this->ensureArgs3)) {
            $output->{'ensureArgs3'} = array_map(function(MyClassEnsureArgs3Item $i) { return $i->toStdClass($includeDefaults); }, $this->ensureArgs3);
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!property_exists($output, (string) $k)) {
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
        $validator->validate($input, self::$_schema);

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
        if (isset($this->ensureArgs1)) {
            $this->ensureArgs1 = (is_string($this->ensureArgs1)) ? ($this->ensureArgs1) : (($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative2) ? (clone $this->ensureArgs1) : (($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative1) ? (clone $this->ensureArgs1) : ($this->ensureArgs1)));
        }
        if (isset($this->ensureArgs2)) {
            $this->ensureArgs2 = clone $this->ensureArgs2;
        }
        if (isset($this->ensureArgs3)) {
            $this->ensureArgs3 = array_map(function(MyClassEnsureArgs3Item $i) { return clone $i; }, $this->ensureArgs3);
        }
    }

    /**
     * Checks if an optional nullable property was explicitly set
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema)
     * @return bool
     */
    public function isOptionalProvided(string $propertyName)
    {
        return array_key_exists($propertyName, $this->_providedOptionals);
    }
}
