<?php

declare(strict_types=1);

namespace Ns\FallbackNamingPreserve_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
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
            'buildFromInput',
            'toArray',
            'validateInput',
            'schema',
            '_defaults',
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
            '_providedOptionals' => [
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
    private static array $_defaults = [
        '_defaults' => [
            'default' => 'foo',
        ],
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_providedOptionals = [];

    /**
     * @var string
     */
    private string $_GLOBALS_1;

    /**
     * @var string
     */
    private string $_GLOBALS_2;

    /**
     * @var string
     */
    private string $_GLOBALS_1_1;

    /**
     * @var string
     */
    private string $_SERVER_1;

    /**
     * @var string
     */
    private string $_GET_1;

    /**
     * @var string
     */
    private string $_POST_1;

    /**
     * @var string
     */
    private string $_FILES_1;

    /**
     * @var string
     */
    private string $_REQUEST_1;

    /**
     * @var string
     */
    private string $_SESSION_1;

    /**
     * @var string
     */
    private string $_ENV_1;

    /**
     * @var string
     */
    private string $_COOKIE_1;

    /**
     * @var string
     */
    private string $_php_errormsg;

    /**
     * @var string
     */
    private string $_http_response_header;

    /**
     * @var string
     */
    private string $_argc;

    /**
     * @var string
     */
    private string $_argv;

    /**
     * @var string
     */
    private string $input;

    /**
     * @var string|null
     */
    private ?string $validate = null;

    /**
     * @var string|null
     */
    private ?string $materializeDefaults = null;

    /**
     * @var string
     */
    private string $obj;

    /**
     * @var string
     */
    private string $includeDefaults;

    /**
     * @var MyClassTestObj|null
     */
    private ?MyClassTestObj $testObj = null;

    /**
     * @var string
     */
    private string $_buildFromInput;

    /**
     * @var string
     */
    private string $_toArray;

    /**
     * @var string
     */
    private string $_validateInput;

    /**
     * @var string
     */
    private string $_schema;

    /**
     * @var string
     */
    private string $_defaults_1;

    /**
     * @var string
     */
    private string $_providedOptionals_1;

    /**
     * @var string
     */
    private string $_clone;

    /**
     * @var string
     */
    private string $__construct_1;

    /**
     * @var string
     */
    private string $__destruct_1;

    /**
     * @var string
     */
    private string $__get_1;

    /**
     * @var string
     */
    private string $__set_1;

    /**
     * @var string
     */
    private string $__call_1;

    /**
     * @var string
     */
    private string $__isset_1;

    /**
     * @var string
     */
    private string $__unset_1;

    /**
     * @var string
     */
    private string $__sleep_1;

    /**
     * @var string
     */
    private string $__wakeup_1;

    /**
     * @var string
     */
    private string $__toString_1;

    /**
     * @var string
     */
    private string $__invoke_1;

    /**
     * @var string
     */
    private string $__debugInfo_1;

    /**
     * @var string
     */
    private string $__clone_1;

    /**
     * @var string
     */
    private string $files;

    /**
     * @var MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string|null
     */
    private MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string|null $ensureArgs1 = null;

    /**
     * @var MyClassEnsureArgs2|null
     */
    private ?MyClassEnsureArgs2 $ensureArgs2 = null;

    /**
     * @var MyClassEnsureArgs3Item[]|null
     */
    private ?array $ensureArgs3 = null;

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
     * @param string $obj
     * @param string $includeDefaults
     * @param string $_buildFromInput
     * @param string $_toArray
     * @param string $_validateInput
     * @param string $_schema
     * @param string $_defaults_1
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
     */
    public function __construct(string $_GLOBALS_1, string $_GLOBALS_2, string $_GLOBALS_1_1, string $_SERVER_1, string $_GET_1, string $_POST_1, string $_FILES_1, string $_REQUEST_1, string $_SESSION_1, string $_ENV_1, string $_COOKIE_1, string $_php_errormsg, string $_http_response_header, string $_argc, string $_argv, string $input, string $obj, string $includeDefaults, string $_buildFromInput, string $_toArray, string $_validateInput, string $_schema, string $_defaults_1, string $_providedOptionals_1, string $_clone, string $__construct_1, string $__destruct_1, string $__get_1, string $__set_1, string $__call_1, string $__isset_1, string $__unset_1, string $__sleep_1, string $__wakeup_1, string $__toString_1, string $__invoke_1, string $__debugInfo_1, string $__clone_1, string $files)
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
        $this->obj = $obj;
        $this->includeDefaults = $includeDefaults;
        $this->_buildFromInput = $_buildFromInput;
        $this->_toArray = $_toArray;
        $this->_validateInput = $_validateInput;
        $this->_schema = $_schema;
        $this->_defaults_1 = $_defaults_1;
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
    }

    /**
     * @return string
     */
    public function get_GLOBALS1(): string
    {
        return $this->_GLOBALS_1;
    }

    /**
     * @return string
     */
    public function get_GLOBALS2(): string
    {
        return $this->_GLOBALS_2;
    }

    /**
     * @return string
     */
    public function get_GLOBALS11(): string
    {
        return $this->_GLOBALS_1_1;
    }

    /**
     * @return string
     */
    public function get_SERVER1(): string
    {
        return $this->_SERVER_1;
    }

    /**
     * @return string
     */
    public function get_GET1(): string
    {
        return $this->_GET_1;
    }

    /**
     * @return string
     */
    public function get_POST1(): string
    {
        return $this->_POST_1;
    }

    /**
     * @return string
     */
    public function get_FILES1(): string
    {
        return $this->_FILES_1;
    }

    /**
     * @return string
     */
    public function get_REQUEST1(): string
    {
        return $this->_REQUEST_1;
    }

    /**
     * @return string
     */
    public function get_SESSION1(): string
    {
        return $this->_SESSION_1;
    }

    /**
     * @return string
     */
    public function get_ENV1(): string
    {
        return $this->_ENV_1;
    }

    /**
     * @return string
     */
    public function get_COOKIE1(): string
    {
        return $this->_COOKIE_1;
    }

    /**
     * @return string
     */
    public function get_PhpErrormsg(): string
    {
        return $this->_php_errormsg;
    }

    /**
     * @return string
     */
    public function get_HttpResponseHeader(): string
    {
        return $this->_http_response_header;
    }

    /**
     * @return string
     */
    public function get_Argc(): string
    {
        return $this->_argc;
    }

    /**
     * @return string
     */
    public function get_Argv(): string
    {
        return $this->_argv;
    }

    /**
     * @return string
     */
    public function getInput(): string
    {
        return $this->input;
    }

    /**
     * @return string|null
     */
    public function getValidate(): ?string
    {
        return $this->validate ?? null;
    }

    /**
     * @return string|null
     */
    public function getMaterializeDefaults(): ?string
    {
        return $this->materializeDefaults ?? null;
    }

    /**
     * @return string
     */
    public function getObj(): string
    {
        return $this->obj;
    }

    /**
     * @return string
     */
    public function getIncludeDefaults(): string
    {
        return $this->includeDefaults;
    }

    /**
     * @return MyClassTestObj|null
     */
    public function getTestObj(): ?MyClassTestObj
    {
        return $this->testObj ?? null;
    }

    /**
     * @return string
     */
    public function get_BuildFromInput(): string
    {
        return $this->_buildFromInput;
    }

    /**
     * @return string
     */
    public function get_ToArray(): string
    {
        return $this->_toArray;
    }

    /**
     * @return string
     */
    public function get_ValidateInput(): string
    {
        return $this->_validateInput;
    }

    /**
     * @return string
     */
    public function get_Schema(): string
    {
        return $this->_schema;
    }

    /**
     * @return string
     */
    public function get_Defaults1(): string
    {
        return $this->_defaults_1;
    }

    /**
     * @return string
     */
    public function get_ProvidedOptionals1(): string
    {
        return $this->_providedOptionals_1;
    }

    /**
     * @return string
     */
    public function get_Clone(): string
    {
        return $this->_clone;
    }

    /**
     * @return string
     */
    public function get__Construct1(): string
    {
        return $this->__construct_1;
    }

    /**
     * @return string
     */
    public function get__Destruct1(): string
    {
        return $this->__destruct_1;
    }

    /**
     * @return string
     */
    public function get__Get1(): string
    {
        return $this->__get_1;
    }

    /**
     * @return string
     */
    public function get__Set1(): string
    {
        return $this->__set_1;
    }

    /**
     * @return string
     */
    public function get__Call1(): string
    {
        return $this->__call_1;
    }

    /**
     * @return string
     */
    public function get__Isset1(): string
    {
        return $this->__isset_1;
    }

    /**
     * @return string
     */
    public function get__Unset1(): string
    {
        return $this->__unset_1;
    }

    /**
     * @return string
     */
    public function get__Sleep1(): string
    {
        return $this->__sleep_1;
    }

    /**
     * @return string
     */
    public function get__Wakeup1(): string
    {
        return $this->__wakeup_1;
    }

    /**
     * @return string
     */
    public function get__ToString1(): string
    {
        return $this->__toString_1;
    }

    /**
     * @return string
     */
    public function get__Invoke1(): string
    {
        return $this->__invoke_1;
    }

    /**
     * @return string
     */
    public function get__DebugInfo1(): string
    {
        return $this->__debugInfo_1;
    }

    /**
     * @return string
     */
    public function get__Clone1(): string
    {
        return $this->__clone_1;
    }

    /**
     * @return string
     */
    public function getFiles(): string
    {
        return $this->files;
    }

    /**
     * @return MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string|null
     */
    public function getEnsureArgs1(): MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string|null
    {
        return $this->ensureArgs1;
    }

    /**
     * @return MyClassEnsureArgs2|null
     */
    public function getEnsureArgs2(): ?MyClassEnsureArgs2
    {
        return $this->ensureArgs2 ?? null;
    }

    /**
     * @return MyClassEnsureArgs3Item[]|null
     */
    public function getEnsureArgs3(): ?array
    {
        return $this->ensureArgs3 ?? null;
    }

    /**
     * @param string $_GLOBALS_1
     * @return self
     * @param bool $validate
     */
    public function with_GLOBALS1(string $_GLOBALS_1, bool $validate = true): self
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
    public function with_GLOBALS2(string $_GLOBALS_2, bool $validate = true): self
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
    public function with_GLOBALS11(string $_GLOBALS_1_1, bool $validate = true): self
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
    public function with_SERVER1(string $_SERVER_1, bool $validate = true): self
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
    public function with_GET1(string $_GET_1, bool $validate = true): self
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
    public function with_POST1(string $_POST_1, bool $validate = true): self
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
    public function with_FILES1(string $_FILES_1, bool $validate = true): self
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
    public function with_REQUEST1(string $_REQUEST_1, bool $validate = true): self
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
    public function with_SESSION1(string $_SESSION_1, bool $validate = true): self
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
    public function with_ENV1(string $_ENV_1, bool $validate = true): self
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
    public function with_COOKIE1(string $_COOKIE_1, bool $validate = true): self
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
    public function with_PhpErrormsg(string $_php_errormsg, bool $validate = true): self
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
    public function with_HttpResponseHeader(string $_http_response_header, bool $validate = true): self
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
    public function with_Argc(string $_argc, bool $validate = true): self
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
    public function with_Argv(string $_argv, bool $validate = true): self
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
    public function withInput(string $input, bool $validate = true): self
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
    public function withValidate(bool $validate = true): self
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
     * @return self
     */
    public function withoutValidate(): self
    {
        $clone = clone $this;
        unset($clone->validate);

        return $clone;
    }

    /**
     * @param string $materializeDefaults
     * @return self
     * @param bool $validate
     */
    public function withMaterializeDefaults(string $materializeDefaults, bool $validate = true): self
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
        $clone->_providedOptionals['materializeDefaults'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutMaterializeDefaults(): self
    {
        $clone = clone $this;
        unset($clone->materializeDefaults);
        unset($clone->_providedOptionals['materializeDefaults']);

        return $clone;
    }

    /**
     * @param string $obj
     * @return self
     * @param bool $validate
     */
    public function withObj(string $obj, bool $validate = true): self
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
     * @param string $includeDefaults
     * @return self
     * @param bool $validate
     */
    public function withIncludeDefaults(string $includeDefaults, bool $validate = true): self
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
    public function withTestObj(MyClassTestObj $testObj): self
    {
        $clone = clone $this;
        $clone->testObj = $testObj;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutTestObj(): self
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
    public function with_BuildFromInput(string $_buildFromInput, bool $validate = true): self
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
    public function with_ToArray(string $_toArray, bool $validate = true): self
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
    public function with_ValidateInput(string $_validateInput, bool $validate = true): self
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
    public function with_Schema(string $_schema, bool $validate = true): self
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
    public function with_Defaults1(string $_defaults_1, bool $validate = true): self
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
     * @param string $_providedOptionals_1
     * @return self
     * @param bool $validate
     */
    public function with_ProvidedOptionals1(string $_providedOptionals_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_providedOptionals_1, self::$schema['properties']['_providedOptionals']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_providedOptionals_1 = $_providedOptionals_1;

        return $clone;
    }

    /**
     * @param string $_clone
     * @return self
     * @param bool $validate
     */
    public function with_Clone(string $_clone, bool $validate = true): self
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
    public function with__Construct1(string $__construct_1, bool $validate = true): self
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
    public function with__Destruct1(string $__destruct_1, bool $validate = true): self
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
    public function with__Get1(string $__get_1, bool $validate = true): self
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
    public function with__Set1(string $__set_1, bool $validate = true): self
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
    public function with__Call1(string $__call_1, bool $validate = true): self
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
    public function with__Isset1(string $__isset_1, bool $validate = true): self
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
    public function with__Unset1(string $__unset_1, bool $validate = true): self
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
    public function with__Sleep1(string $__sleep_1, bool $validate = true): self
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
    public function with__Wakeup1(string $__wakeup_1, bool $validate = true): self
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
    public function with__ToString1(string $__toString_1, bool $validate = true): self
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
    public function with__Invoke1(string $__invoke_1, bool $validate = true): self
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
    public function with__DebugInfo1(string $__debugInfo_1, bool $validate = true): self
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
    public function with__Clone1(string $__clone_1, bool $validate = true): self
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
    public function withFiles(string $files, bool $validate = true): self
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
     * @param MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string $ensureArgs1
     * @return self
     */
    public function withEnsureArgs1(MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string $ensureArgs1): self
    {
        $clone = clone $this;
        $clone->ensureArgs1 = $ensureArgs1;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutEnsureArgs1(): self
    {
        $clone = clone $this;
        unset($clone->ensureArgs1);

        return $clone;
    }

    /**
     * @param MyClassEnsureArgs2 $ensureArgs2
     * @return self
     */
    public function withEnsureArgs2(MyClassEnsureArgs2 $ensureArgs2): self
    {
        $clone = clone $this;
        $clone->ensureArgs2 = $ensureArgs2;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutEnsureArgs2(): self
    {
        $clone = clone $this;
        unset($clone->ensureArgs2);

        return $clone;
    }

    /**
     * @param MyClassEnsureArgs3Item[] $ensureArgs3
     * @return self
     * @param bool $validate
     */
    public function withEnsureArgs3(array $ensureArgs3, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($ensureArgs3, self::$schema['properties']['ensureArgs3']);
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
    public function withoutEnsureArgs3(): self
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
    public static function buildFromInput(array|object $input, bool $validate = true, bool $materializeDefaults = false): MyClass
    {
        $_input = $input;
        unset($input);
        $_validate = $validate;
        unset($validate);
        $_materializeDefaults = $materializeDefaults;
        unset($materializeDefaults);

        $_input = is_array($_input)
            ? \JsonSchema\Validator::arrayToObjectRecursive($_input)
            : ($_materializeDefaults ? clone $_input : $_input);

        if ($_materializeDefaults) {
            foreach (self::$_defaults as $__k => $__v) {
                if (!property_exists($_input, (string) $__k)) {
                   $_input->{$__k} = ($__v['type'] ?? null) === 'object'
                       ? \JsonSchema\Validator::arrayToObjectRecursive($__v['default'])
                       : $__v['default'];
                }
            }
        }

        if ($_validate) {
            static::validateInput($_input);
        }

        $__providedOptionals = [];
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
        $validate = isset($_input->{'validate'}) ? ($_input->{'validate'}) : null;
        $materializeDefaults = property_exists($_input, 'materializeDefaults') ? (($_input->{'materializeDefaults'} !== null) ? ($_input->{'materializeDefaults'}) : null) : null;
        if (property_exists($_input, 'materializeDefaults')) {
            $__providedOptionals['materializeDefaults'] = true;
        }
        $obj = $_input->{'obj'};
        $includeDefaults = $_input->{'includeDefaults'};
        $testObj = isset($_input->{'testObj'}) ? (MyClassTestObj::buildFromInput($_input->{'testObj'}, $_validate, $_materializeDefaults)) : null;
        $_buildFromInput = $_input->{'buildFromInput'};
        $_toArray = $_input->{'toArray'};
        $_validateInput = $_input->{'validateInput'};
        $_schema = $_input->{'schema'};
        $_defaults_1 = $_input->{'_defaults'};
        $_providedOptionals_1 = $_input->{'_providedOptionals'};
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
        $ensureArgs1 = isset($_input->{'ensureArgs1'}) ? (match (true) {
            MyClassEnsureArgs1Alternative1::validateInput($_input->{'ensureArgs1'}, true) => MyClassEnsureArgs1Alternative1::buildFromInput($_input->{'ensureArgs1'}, $_validate, $_materializeDefaults),
            MyClassEnsureArgs1Alternative2::validateInput($_input->{'ensureArgs1'}, true) => MyClassEnsureArgs1Alternative2::buildFromInput($_input->{'ensureArgs1'}, $_validate, $_materializeDefaults),
            is_string($_input->{'ensureArgs1'}) => $_input->{'ensureArgs1'},
            default => null,
        }) : null;
        $ensureArgs2 = isset($_input->{'ensureArgs2'}) ? (MyClassEnsureArgs2::buildFromInput($_input->{'ensureArgs2'}, $_validate, $_materializeDefaults)) : null;
        $ensureArgs3 = isset($_input->{'ensureArgs3'}) ? (array_map(fn (array|object $i): MyClassEnsureArgs3Item => MyClassEnsureArgs3Item::buildFromInput($i, $_validate, $_materializeDefaults), $_input->{'ensureArgs3'})) : null;

        $_obj = new self($_GLOBALS_1, $_GLOBALS_2, $_GLOBALS_1_1, $_SERVER_1, $_GET_1, $_POST_1, $_FILES_1, $_REQUEST_1, $_SESSION_1, $_ENV_1, $_COOKIE_1, $_php_errormsg, $_http_response_header, $_argc, $_argv, $input, $obj, $includeDefaults, $_buildFromInput, $_toArray, $_validateInput, $_schema, $_defaults_1, $_providedOptionals_1, $_clone, $__construct_1, $__destruct_1, $__get_1, $__set_1, $__call_1, $__isset_1, $__unset_1, $__sleep_1, $__wakeup_1, $__toString_1, $__invoke_1, $__debugInfo_1, $__clone_1, $files);
        $_obj->validate = $validate;
        $_obj->materializeDefaults = $materializeDefaults;
        $_obj->testObj = $testObj;
        $_obj->ensureArgs1 = $ensureArgs1;
        $_obj->ensureArgs2 = $ensureArgs2;
        $_obj->ensureArgs3 = $ensureArgs3;
        $_obj->_providedOptionals = $__providedOptionals;
        return $_obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return array Converted array
     */
    public function toArray(bool $includeDefaults = false): array
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
        $output['buildFromInput'] = $this->_buildFromInput;
        $output['toArray'] = $this->_toArray;
        $output['validateInput'] = $this->_validateInput;
        $output['schema'] = $this->_schema;
        $output['_defaults'] = $this->_defaults_1;
        $output['_providedOptionals'] = $this->_providedOptionals_1;
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
        if (isset($this->ensureArgs1)) {
            $output['ensureArgs1'] = match (true) {
                $this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative1,
                $this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative2 => ($this->ensureArgs1)->toArray($includeDefaults),
                is_string($this->ensureArgs1) => $this->ensureArgs1,
            };
        }
        if (isset($this->ensureArgs2)) {
            $output['ensureArgs2'] = ($this->ensureArgs2)->toArray($includeDefaults);
        }
        if (isset($this->ensureArgs3)) {
            $output['ensureArgs3'] = array_map(fn (MyClassEnsureArgs3Item $i) => $i->toArray(), $this->ensureArgs3);
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
    public function toStdClass(bool $includeDefaults = false): \stdClass
    {
        $output = new \stdClass();
        $output->{'_GLOBALS'} = $this->_GLOBALS_1;
        $output->{'GLOBALS'} = $this->_GLOBALS_2;
        $output->{'GLOBALS_1'} = $this->_GLOBALS_1_1;
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
        $output->{'buildFromInput'} = $this->_buildFromInput;
        $output->{'toArray'} = $this->_toArray;
        $output->{'validateInput'} = $this->_validateInput;
        $output->{'schema'} = $this->_schema;
        $output->{'_defaults'} = $this->_defaults_1;
        $output->{'_providedOptionals'} = $this->_providedOptionals_1;
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
        if (isset($this->ensureArgs1)) {
            $output->{'ensureArgs1'} = match (true) {
                $this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative1,
                $this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative2 => ($this->ensureArgs1)->toStdClass($includeDefaults),
                is_string($this->ensureArgs1) => $this->ensureArgs1,
            };
        }
        if (isset($this->ensureArgs2)) {
            $output->{'ensureArgs2'} = ($this->ensureArgs2)->toStdClass($includeDefaults);
        }
        if (isset($this->ensureArgs3)) {
            $output->{'ensureArgs3'} = array_map(fn (MyClassEnsureArgs3Item $i) => $i->toStdClass($includeDefaults), $this->ensureArgs3);
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
    public static function validateInput(array|object $input, bool $return = false): bool
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
        if (isset($this->testObj)) {
            $this->testObj = clone $this->testObj;
        }
        if (isset($this->ensureArgs1)) {
            $this->ensureArgs1 = match (true) {
                $this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative1,
                $this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative2 => clone $this->ensureArgs1,
                is_string($this->ensureArgs1) => $this->ensureArgs1,
            };
        }
        if (isset($this->ensureArgs2)) {
            $this->ensureArgs2 = clone $this->ensureArgs2;
        }
        if (isset($this->ensureArgs3)) {
            $this->ensureArgs3 = array_map(fn (MyClassEnsureArgs3Item $i) => clone $i, $this->ensureArgs3);
        }
    }

    /**
     * Checks if an optional nullable property was explicitly set
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema)
     * @return bool
     */
    public function isOptionalProvided(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->_providedOptionals);
    }
}
