<?php

declare(strict_types=1);

namespace Ns\FallbackNaming_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
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
    private static array $_defaults = [
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
    private string $_GLOBALS1_1;

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
    private string $_phpErrormsg;

    /**
     * @var string
     */
    private string $_httpResponseHeader;

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
    private string $_fromInput_1;

    /**
     * @var string
     */
    private string $_toArray_1;

    /**
     * @var string
     */
    private string $_toStdClass_1;

    /**
     * @var string
     */
    private string $_validateInput_1;

    /**
     * @var string
     */
    private string $_schema_1;

    /**
     * @var string
     */
    private string $_schema_2;

    /**
     * @var string
     */
    private string $_defaults_1;

    /**
     * @var string
     */
    private string $_defaults_2;

    /**
     * @var string
     */
    private string $_providedOptionals_1;

    /**
     * @var string|null
     */
    private ?string $_providedOptionals_2 = null;

    /**
     * @var string
     */
    private string $_clone_1;

    /**
     * @var string
     */
    private string $_construct_1;

    /**
     * @var string
     */
    private string $_destruct_1;

    /**
     * @var string
     */
    private string $_get_2;

    /**
     * @var string
     */
    private string $_set_1;

    /**
     * @var string
     */
    private string $_call_1;

    /**
     * @var string
     */
    private string $_isset_1;

    /**
     * @var string
     */
    private string $_unset_1;

    /**
     * @var string
     */
    private string $_sleep_1;

    /**
     * @var string
     */
    private string $_wakeup_1;

    /**
     * @var string
     */
    private string $_toString_1;

    /**
     * @var string
     */
    private string $_invoke_1;

    /**
     * @var string
     */
    private string $_debugInfo_1;

    /**
     * @var string
     */
    private string $_clone_2;

    /**
     * @var string
     */
    private string $files;

    /**
     * @var string
     */
    private string $_this;

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
     * @param string $_GLOBALS1_1
     * @param string $_SERVER_1
     * @param string $_GET_1
     * @param string $_POST_1
     * @param string $_FILES_1
     * @param string $_REQUEST_1
     * @param string $_SESSION_1
     * @param string $_ENV_1
     * @param string $_COOKIE_1
     * @param string $_phpErrormsg
     * @param string $_httpResponseHeader
     * @param string $_argc
     * @param string $_argv
     * @param string $input
     * @param string $obj
     * @param string $includeDefaults
     * @param string $_fromInput_1
     * @param string $_toArray_1
     * @param string $_toStdClass_1
     * @param string $_validateInput_1
     * @param string $_schema_1
     * @param string $_schema_2
     * @param string $_defaults_1
     * @param string $_defaults_2
     * @param string $_providedOptionals_1
     * @param string $_clone_1
     * @param string $_construct_1
     * @param string $_destruct_1
     * @param string $_get_2
     * @param string $_set_1
     * @param string $_call_1
     * @param string $_isset_1
     * @param string $_unset_1
     * @param string $_sleep_1
     * @param string $_wakeup_1
     * @param string $_toString_1
     * @param string $_invoke_1
     * @param string $_debugInfo_1
     * @param string $_clone_2
     * @param string $files
     * @param string $_this
     */
    public function __construct(string $_GLOBALS_1, string $_GLOBALS_2, string $_GLOBALS1_1, string $_SERVER_1, string $_GET_1, string $_POST_1, string $_FILES_1, string $_REQUEST_1, string $_SESSION_1, string $_ENV_1, string $_COOKIE_1, string $_phpErrormsg, string $_httpResponseHeader, string $_argc, string $_argv, string $input, string $obj, string $includeDefaults, string $_fromInput_1, string $_toArray_1, string $_toStdClass_1, string $_validateInput_1, string $_schema_1, string $_schema_2, string $_defaults_1, string $_defaults_2, string $_providedOptionals_1, string $_clone_1, string $_construct_1, string $_destruct_1, string $_get_2, string $_set_1, string $_call_1, string $_isset_1, string $_unset_1, string $_sleep_1, string $_wakeup_1, string $_toString_1, string $_invoke_1, string $_debugInfo_1, string $_clone_2, string $files, string $_this)
    {
        $this->_GLOBALS_1 = $_GLOBALS_1;
        $this->_GLOBALS_2 = $_GLOBALS_2;
        $this->_GLOBALS1_1 = $_GLOBALS1_1;
        $this->_SERVER_1 = $_SERVER_1;
        $this->_GET_1 = $_GET_1;
        $this->_POST_1 = $_POST_1;
        $this->_FILES_1 = $_FILES_1;
        $this->_REQUEST_1 = $_REQUEST_1;
        $this->_SESSION_1 = $_SESSION_1;
        $this->_ENV_1 = $_ENV_1;
        $this->_COOKIE_1 = $_COOKIE_1;
        $this->_phpErrormsg = $_phpErrormsg;
        $this->_httpResponseHeader = $_httpResponseHeader;
        $this->_argc = $_argc;
        $this->_argv = $_argv;
        $this->input = $input;
        $this->obj = $obj;
        $this->includeDefaults = $includeDefaults;
        $this->_fromInput_1 = $_fromInput_1;
        $this->_toArray_1 = $_toArray_1;
        $this->_toStdClass_1 = $_toStdClass_1;
        $this->_validateInput_1 = $_validateInput_1;
        $this->_schema_1 = $_schema_1;
        $this->_schema_2 = $_schema_2;
        $this->_defaults_1 = $_defaults_1;
        $this->_defaults_2 = $_defaults_2;
        $this->_providedOptionals_1 = $_providedOptionals_1;
        $this->_clone_1 = $_clone_1;
        $this->_construct_1 = $_construct_1;
        $this->_destruct_1 = $_destruct_1;
        $this->_get_2 = $_get_2;
        $this->_set_1 = $_set_1;
        $this->_call_1 = $_call_1;
        $this->_isset_1 = $_isset_1;
        $this->_unset_1 = $_unset_1;
        $this->_sleep_1 = $_sleep_1;
        $this->_wakeup_1 = $_wakeup_1;
        $this->_toString_1 = $_toString_1;
        $this->_invoke_1 = $_invoke_1;
        $this->_debugInfo_1 = $_debugInfo_1;
        $this->_clone_2 = $_clone_2;
        $this->files = $files;
        $this->_this = $_this;
    }

    /**
     * @return string
     */
    public function getGLOBALS1(): string
    {
        return $this->_GLOBALS_1;
    }

    /**
     * @param string $_GLOBALS_1
     * @return self
     * @param bool $validate
     */
    public function withGLOBALS1(string $_GLOBALS_1, bool $validate = true): self
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
    public function getGLOBALS2(): string
    {
        return $this->_GLOBALS_2;
    }

    /**
     * @param string $_GLOBALS_2
     * @return self
     * @param bool $validate
     */
    public function withGLOBALS2(string $_GLOBALS_2, bool $validate = true): self
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
    public function getGLOBALS11(): string
    {
        return $this->_GLOBALS1_1;
    }

    /**
     * @param string $_GLOBALS1_1
     * @return self
     * @param bool $validate
     */
    public function withGLOBALS11(string $_GLOBALS1_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_GLOBALS1_1, self::$_schema['properties']['GLOBALS_1']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_GLOBALS1_1 = $_GLOBALS1_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getSERVER1(): string
    {
        return $this->_SERVER_1;
    }

    /**
     * @param string $_SERVER_1
     * @return self
     * @param bool $validate
     */
    public function withSERVER1(string $_SERVER_1, bool $validate = true): self
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
    public function getGET1(): string
    {
        return $this->_GET_1;
    }

    /**
     * @param string $_GET_1
     * @return self
     * @param bool $validate
     */
    public function withGET1(string $_GET_1, bool $validate = true): self
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
    public function getPOST1(): string
    {
        return $this->_POST_1;
    }

    /**
     * @param string $_POST_1
     * @return self
     * @param bool $validate
     */
    public function withPOST1(string $_POST_1, bool $validate = true): self
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
    public function getFILES1(): string
    {
        return $this->_FILES_1;
    }

    /**
     * @param string $_FILES_1
     * @return self
     * @param bool $validate
     */
    public function withFILES1(string $_FILES_1, bool $validate = true): self
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
    public function getREQUEST1(): string
    {
        return $this->_REQUEST_1;
    }

    /**
     * @param string $_REQUEST_1
     * @return self
     * @param bool $validate
     */
    public function withREQUEST1(string $_REQUEST_1, bool $validate = true): self
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
    public function getSESSION1(): string
    {
        return $this->_SESSION_1;
    }

    /**
     * @param string $_SESSION_1
     * @return self
     * @param bool $validate
     */
    public function withSESSION1(string $_SESSION_1, bool $validate = true): self
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
    public function getENV1(): string
    {
        return $this->_ENV_1;
    }

    /**
     * @param string $_ENV_1
     * @return self
     * @param bool $validate
     */
    public function withENV1(string $_ENV_1, bool $validate = true): self
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
    public function getCOOKIE1(): string
    {
        return $this->_COOKIE_1;
    }

    /**
     * @param string $_COOKIE_1
     * @return self
     * @param bool $validate
     */
    public function withCOOKIE1(string $_COOKIE_1, bool $validate = true): self
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
    public function getPhpErrormsg(): string
    {
        return $this->_phpErrormsg;
    }

    /**
     * @param string $_phpErrormsg
     * @return self
     * @param bool $validate
     */
    public function withPhpErrormsg(string $_phpErrormsg, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_phpErrormsg, self::$_schema['properties']['php_errormsg']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_phpErrormsg = $_phpErrormsg;

        return $clone;
    }

    /**
     * @return string
     */
    public function getHttpResponseHeader(): string
    {
        return $this->_httpResponseHeader;
    }

    /**
     * @param string $_httpResponseHeader
     * @return self
     * @param bool $validate
     */
    public function withHttpResponseHeader(string $_httpResponseHeader, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_httpResponseHeader, self::$_schema['properties']['http_response_header']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_httpResponseHeader = $_httpResponseHeader;

        return $clone;
    }

    /**
     * @return string
     */
    public function getArgc(): string
    {
        return $this->_argc;
    }

    /**
     * @param string $_argc
     * @return self
     * @param bool $validate
     */
    public function withArgc(string $_argc, bool $validate = true): self
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
    public function getArgv(): string
    {
        return $this->_argv;
    }

    /**
     * @param string $_argv
     * @return self
     * @param bool $validate
     */
    public function withArgv(string $_argv, bool $validate = true): self
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
    public function getInput(): string
    {
        return $this->input;
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
    public function getValidate(): ?string
    {
        return $this->validate ?? null;
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
    public function withoutValidate(): self
    {
        $clone = clone $this;
        unset($clone->validate);

        return $clone;
    }

    /**
     * @return string|null
     */
    public function getMaterializeDefaults(): ?string
    {
        return $this->materializeDefaults ?? null;
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
    public function withoutMaterializeDefaults(): self
    {
        $clone = clone $this;
        unset($clone->materializeDefaults);
        unset($clone->_providedOptionals['materializeDefaults']);

        return $clone;
    }

    /**
     * @return string
     */
    public function getObj(): string
    {
        return $this->obj;
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
    public function getIncludeDefaults(): string
    {
        return $this->includeDefaults;
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
    public function getTestObj(): ?MyClassTestObj
    {
        return $this->testObj ?? null;
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
     * @return string
     */
    public function getFromInput1(): string
    {
        return $this->_fromInput_1;
    }

    /**
     * @param string $_fromInput_1
     * @return self
     * @param bool $validate
     */
    public function withFromInput1(string $_fromInput_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_fromInput_1, self::$_schema['properties']['fromInput']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_fromInput_1 = $_fromInput_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getToArray1(): string
    {
        return $this->_toArray_1;
    }

    /**
     * @param string $_toArray_1
     * @return self
     * @param bool $validate
     */
    public function withToArray1(string $_toArray_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_toArray_1, self::$_schema['properties']['toArray']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_toArray_1 = $_toArray_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getToStdClass1(): string
    {
        return $this->_toStdClass_1;
    }

    /**
     * @param string $_toStdClass_1
     * @return self
     * @param bool $validate
     */
    public function withToStdClass1(string $_toStdClass_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_toStdClass_1, self::$_schema['properties']['toStdClass']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_toStdClass_1 = $_toStdClass_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getValidateInput1(): string
    {
        return $this->_validateInput_1;
    }

    /**
     * @param string $_validateInput_1
     * @return self
     * @param bool $validate
     */
    public function withValidateInput1(string $_validateInput_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_validateInput_1, self::$_schema['properties']['validateInput']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_validateInput_1 = $_validateInput_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getSchema1(): string
    {
        return $this->_schema_1;
    }

    /**
     * @param string $_schema_1
     * @return self
     * @param bool $validate
     */
    public function withSchema1(string $_schema_1, bool $validate = true): self
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
    public function getSchema2(): string
    {
        return $this->_schema_2;
    }

    /**
     * @param string $_schema_2
     * @return self
     * @param bool $validate
     */
    public function withSchema2(string $_schema_2, bool $validate = true): self
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
    public function getDefaults1(): string
    {
        return $this->_defaults_1;
    }

    /**
     * @param string $_defaults_1
     * @return self
     * @param bool $validate
     */
    public function withDefaults1(string $_defaults_1, bool $validate = true): self
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
    public function getDefaults2(): string
    {
        return $this->_defaults_2;
    }

    /**
     * @param string $_defaults_2
     * @return self
     * @param bool $validate
     */
    public function withDefaults2(string $_defaults_2, bool $validate = true): self
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
    public function getProvidedOptionals1(): string
    {
        return $this->_providedOptionals_1;
    }

    /**
     * @param string $_providedOptionals_1
     * @return self
     * @param bool $validate
     */
    public function withProvidedOptionals1(string $_providedOptionals_1, bool $validate = true): self
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
    public function getProvidedOptionals2(): ?string
    {
        return $this->_providedOptionals_2 ?? null;
    }

    /**
     * @param string $_providedOptionals_2
     * @return self
     * @param bool $validate
     */
    public function withProvidedOptionals2(string $_providedOptionals_2, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_providedOptionals_2, self::$_schema['properties']['__providedOptionals']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_providedOptionals_2 = $_providedOptionals_2;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutProvidedOptionals2(): self
    {
        $clone = clone $this;
        unset($clone->_providedOptionals_2);

        return $clone;
    }

    /**
     * @return string
     */
    public function getClone1(): string
    {
        return $this->_clone_1;
    }

    /**
     * @param string $_clone_1
     * @return self
     * @param bool $validate
     */
    public function withClone1(string $_clone_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_clone_1, self::$_schema['properties']['clone']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_clone_1 = $_clone_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getConstruct1(): string
    {
        return $this->_construct_1;
    }

    /**
     * @param string $_construct_1
     * @return self
     * @param bool $validate
     */
    public function withConstruct1(string $_construct_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_construct_1, self::$_schema['properties']['__construct']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_construct_1 = $_construct_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getDestruct1(): string
    {
        return $this->_destruct_1;
    }

    /**
     * @param string $_destruct_1
     * @return self
     * @param bool $validate
     */
    public function withDestruct1(string $_destruct_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_destruct_1, self::$_schema['properties']['__destruct']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_destruct_1 = $_destruct_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getGet2(): string
    {
        return $this->_get_2;
    }

    /**
     * @param string $_get_2
     * @return self
     * @param bool $validate
     */
    public function withGet2(string $_get_2, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_get_2, self::$_schema['properties']['__get']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_get_2 = $_get_2;

        return $clone;
    }

    /**
     * @return string
     */
    public function getSet1(): string
    {
        return $this->_set_1;
    }

    /**
     * @param string $_set_1
     * @return self
     * @param bool $validate
     */
    public function withSet1(string $_set_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_set_1, self::$_schema['properties']['__set']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_set_1 = $_set_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getCall1(): string
    {
        return $this->_call_1;
    }

    /**
     * @param string $_call_1
     * @return self
     * @param bool $validate
     */
    public function withCall1(string $_call_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_call_1, self::$_schema['properties']['__call']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_call_1 = $_call_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getIsset1(): string
    {
        return $this->_isset_1;
    }

    /**
     * @param string $_isset_1
     * @return self
     * @param bool $validate
     */
    public function withIsset1(string $_isset_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_isset_1, self::$_schema['properties']['__isset']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_isset_1 = $_isset_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getUnset1(): string
    {
        return $this->_unset_1;
    }

    /**
     * @param string $_unset_1
     * @return self
     * @param bool $validate
     */
    public function withUnset1(string $_unset_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_unset_1, self::$_schema['properties']['__unset']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_unset_1 = $_unset_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getSleep1(): string
    {
        return $this->_sleep_1;
    }

    /**
     * @param string $_sleep_1
     * @return self
     * @param bool $validate
     */
    public function withSleep1(string $_sleep_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_sleep_1, self::$_schema['properties']['__sleep']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_sleep_1 = $_sleep_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getWakeup1(): string
    {
        return $this->_wakeup_1;
    }

    /**
     * @param string $_wakeup_1
     * @return self
     * @param bool $validate
     */
    public function withWakeup1(string $_wakeup_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_wakeup_1, self::$_schema['properties']['__wakeup']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_wakeup_1 = $_wakeup_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getToString1(): string
    {
        return $this->_toString_1;
    }

    /**
     * @param string $_toString_1
     * @return self
     * @param bool $validate
     */
    public function withToString1(string $_toString_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_toString_1, self::$_schema['properties']['__toString']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_toString_1 = $_toString_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getInvoke1(): string
    {
        return $this->_invoke_1;
    }

    /**
     * @param string $_invoke_1
     * @return self
     * @param bool $validate
     */
    public function withInvoke1(string $_invoke_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_invoke_1, self::$_schema['properties']['__invoke']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_invoke_1 = $_invoke_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getDebugInfo1(): string
    {
        return $this->_debugInfo_1;
    }

    /**
     * @param string $_debugInfo_1
     * @return self
     * @param bool $validate
     */
    public function withDebugInfo1(string $_debugInfo_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_debugInfo_1, self::$_schema['properties']['__debugInfo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_debugInfo_1 = $_debugInfo_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getClone2(): string
    {
        return $this->_clone_2;
    }

    /**
     * @param string $_clone_2
     * @return self
     * @param bool $validate
     */
    public function withClone2(string $_clone_2, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_clone_2, self::$_schema['properties']['__clone']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_clone_2 = $_clone_2;

        return $clone;
    }

    /**
     * @return string
     */
    public function getFiles(): string
    {
        return $this->files;
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
    public function getThis(): string
    {
        return $this->_this;
    }

    /**
     * @param string $_this
     * @return self
     * @param bool $validate
     */
    public function withThis(string $_this, bool $validate = true): self
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
    public function getEnsureArgs1(): MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string|null
    {
        return $this->ensureArgs1;
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
     * @return MyClassEnsureArgs2|null
     */
    public function getEnsureArgs2(): ?MyClassEnsureArgs2
    {
        return $this->ensureArgs2 ?? null;
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
     * @return MyClassEnsureArgs3Item[]|null
     */
    public function getEnsureArgs3(): ?array
    {
        return $this->ensureArgs3 ?? null;
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
    public static function fromInput(array|object $input, bool $validate = true, bool $materializeDefaults = false): MyClass
    {
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

        $__providedOptionals = [];
        $_GLOBALS_1 = $input->{'_GLOBALS'};
        $_GLOBALS_2 = $input->{'GLOBALS'};
        $_GLOBALS1_1 = $input->{'GLOBALS_1'};
        $_SERVER_1 = $input->{'_SERVER'};
        $_GET_1 = $input->{'_GET'};
        $_POST_1 = $input->{'_POST'};
        $_FILES_1 = $input->{'_FILES'};
        $_REQUEST_1 = $input->{'_REQUEST'};
        $_SESSION_1 = $input->{'_SESSION'};
        $_ENV_1 = $input->{'_ENV'};
        $_COOKIE_1 = $input->{'_COOKIE'};
        $_phpErrormsg = $input->{'php_errormsg'};
        $_httpResponseHeader = $input->{'http_response_header'};
        $_argc = $input->{'argc'};
        $_argv = $input->{'argv'};
        $_input = $input->{'input'};
        $_validate = isset($input->{'validate'}) ? $input->{'validate'} : null;
        $_materializeDefaults = property_exists($input, 'materializeDefaults') ? ($input->{'materializeDefaults'} !== null ? $input->{'materializeDefaults'} : null) : null;
        if (property_exists($input, 'materializeDefaults')) {
            $__providedOptionals['materializeDefaults'] = true;
        }
        $_obj = $input->{'obj'};
        $includeDefaults = $input->{'includeDefaults'};
        $testObj = isset($input->{'testObj'}) ? MyClassTestObj::fromInput($input->{'testObj'}, $validate, $materializeDefaults) : null;
        $_fromInput_1 = $input->{'fromInput'};
        $_toArray_1 = $input->{'toArray'};
        $_toStdClass_1 = $input->{'toStdClass'};
        $_validateInput_1 = $input->{'validateInput'};
        $_schema_1 = $input->{'_schema'};
        $_schema_2 = $input->{'schema'};
        $_defaults_1 = $input->{'_defaults'};
        $_defaults_2 = $input->{'defaults'};
        $_providedOptionals_1 = $input->{'_providedOptionals'};
        $_providedOptionals_2 = isset($input->{'__providedOptionals'}) ? $input->{'__providedOptionals'} : null;
        $_clone_1 = $input->{'clone'};
        $_construct_1 = $input->{'__construct'};
        $_destruct_1 = $input->{'__destruct'};
        $_get_2 = $input->{'__get'};
        $_set_1 = $input->{'__set'};
        $_call_1 = $input->{'__call'};
        $_isset_1 = $input->{'__isset'};
        $_unset_1 = $input->{'__unset'};
        $_sleep_1 = $input->{'__sleep'};
        $_wakeup_1 = $input->{'__wakeup'};
        $_toString_1 = $input->{'__toString'};
        $_invoke_1 = $input->{'__invoke'};
        $_debugInfo_1 = $input->{'__debugInfo'};
        $_clone_2 = $input->{'__clone'};
        $files = $input->{'files'};
        $_this = $input->{'this'};
        $ensureArgs1 = isset($input->{'ensureArgs1'}) ? match (true) {
            MyClassEnsureArgs1Alternative1::validateInput($input->{'ensureArgs1'}, true) => MyClassEnsureArgs1Alternative1::fromInput($input->{'ensureArgs1'}, $validate, $materializeDefaults),
            MyClassEnsureArgs1Alternative2::validateInput($input->{'ensureArgs1'}, true) => MyClassEnsureArgs1Alternative2::fromInput($input->{'ensureArgs1'}, $validate, $materializeDefaults),
            is_string($input->{'ensureArgs1'}) => $input->{'ensureArgs1'},
            default => null,
        } : null;
        $ensureArgs2 = isset($input->{'ensureArgs2'}) ? MyClassEnsureArgs2::fromInput($input->{'ensureArgs2'}, $validate, $materializeDefaults) : null;
        $ensureArgs3 = isset($input->{'ensureArgs3'}) ? array_map(fn (array|object $i): MyClassEnsureArgs3Item => MyClassEnsureArgs3Item::fromInput($i, $validate, $materializeDefaults), $input->{'ensureArgs3'}) : null;

        $obj = new self($_GLOBALS_1, $_GLOBALS_2, $_GLOBALS1_1, $_SERVER_1, $_GET_1, $_POST_1, $_FILES_1, $_REQUEST_1, $_SESSION_1, $_ENV_1, $_COOKIE_1, $_phpErrormsg, $_httpResponseHeader, $_argc, $_argv, $_input, $_obj, $includeDefaults, $_fromInput_1, $_toArray_1, $_toStdClass_1, $_validateInput_1, $_schema_1, $_schema_2, $_defaults_1, $_defaults_2, $_providedOptionals_1, $_clone_1, $_construct_1, $_destruct_1, $_get_2, $_set_1, $_call_1, $_isset_1, $_unset_1, $_sleep_1, $_wakeup_1, $_toString_1, $_invoke_1, $_debugInfo_1, $_clone_2, $files, $_this);
        $obj->validate = $_validate;
        $obj->materializeDefaults = $_materializeDefaults;
        $obj->testObj = $testObj;
        $obj->_providedOptionals_2 = $_providedOptionals_2;
        $obj->ensureArgs1 = $ensureArgs1;
        $obj->ensureArgs2 = $ensureArgs2;
        $obj->ensureArgs3 = $ensureArgs3;
        $obj->_providedOptionals = $__providedOptionals;
        return $obj;
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
        $output['GLOBALS_1'] = $this->_GLOBALS1_1;
        $output['_SERVER'] = $this->_SERVER_1;
        $output['_GET'] = $this->_GET_1;
        $output['_POST'] = $this->_POST_1;
        $output['_FILES'] = $this->_FILES_1;
        $output['_REQUEST'] = $this->_REQUEST_1;
        $output['_SESSION'] = $this->_SESSION_1;
        $output['_ENV'] = $this->_ENV_1;
        $output['_COOKIE'] = $this->_COOKIE_1;
        $output['php_errormsg'] = $this->_phpErrormsg;
        $output['http_response_header'] = $this->_httpResponseHeader;
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
        $output['fromInput'] = $this->_fromInput_1;
        $output['toArray'] = $this->_toArray_1;
        $output['toStdClass'] = $this->_toStdClass_1;
        $output['validateInput'] = $this->_validateInput_1;
        $output['_schema'] = $this->_schema_1;
        $output['schema'] = $this->_schema_2;
        $output['_defaults'] = $this->_defaults_1;
        $output['defaults'] = $this->_defaults_2;
        $output['_providedOptionals'] = $this->_providedOptionals_1;
        if (isset($this->_providedOptionals_2)) {
            $output['__providedOptionals'] = $this->_providedOptionals_2;
        }
        $output['clone'] = $this->_clone_1;
        $output['__construct'] = $this->_construct_1;
        $output['__destruct'] = $this->_destruct_1;
        $output['__get'] = $this->_get_2;
        $output['__set'] = $this->_set_1;
        $output['__call'] = $this->_call_1;
        $output['__isset'] = $this->_isset_1;
        $output['__unset'] = $this->_unset_1;
        $output['__sleep'] = $this->_sleep_1;
        $output['__wakeup'] = $this->_wakeup_1;
        $output['__toString'] = $this->_toString_1;
        $output['__invoke'] = $this->_invoke_1;
        $output['__debugInfo'] = $this->_debugInfo_1;
        $output['__clone'] = $this->_clone_2;
        $output['files'] = $this->files;
        $output['this'] = $this->_this;
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
        $output->{'GLOBALS_1'} = $this->_GLOBALS1_1;
        $output->{'_SERVER'} = $this->_SERVER_1;
        $output->{'_GET'} = $this->_GET_1;
        $output->{'_POST'} = $this->_POST_1;
        $output->{'_FILES'} = $this->_FILES_1;
        $output->{'_REQUEST'} = $this->_REQUEST_1;
        $output->{'_SESSION'} = $this->_SESSION_1;
        $output->{'_ENV'} = $this->_ENV_1;
        $output->{'_COOKIE'} = $this->_COOKIE_1;
        $output->{'php_errormsg'} = $this->_phpErrormsg;
        $output->{'http_response_header'} = $this->_httpResponseHeader;
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
        $output->{'fromInput'} = $this->_fromInput_1;
        $output->{'toArray'} = $this->_toArray_1;
        $output->{'toStdClass'} = $this->_toStdClass_1;
        $output->{'validateInput'} = $this->_validateInput_1;
        $output->{'_schema'} = $this->_schema_1;
        $output->{'schema'} = $this->_schema_2;
        $output->{'_defaults'} = $this->_defaults_1;
        $output->{'defaults'} = $this->_defaults_2;
        $output->{'_providedOptionals'} = $this->_providedOptionals_1;
        if (isset($this->_providedOptionals_2)) {
            $output->{'__providedOptionals'} = $this->_providedOptionals_2;
        }
        $output->{'clone'} = $this->_clone_1;
        $output->{'__construct'} = $this->_construct_1;
        $output->{'__destruct'} = $this->_destruct_1;
        $output->{'__get'} = $this->_get_2;
        $output->{'__set'} = $this->_set_1;
        $output->{'__call'} = $this->_call_1;
        $output->{'__isset'} = $this->_isset_1;
        $output->{'__unset'} = $this->_unset_1;
        $output->{'__sleep'} = $this->_sleep_1;
        $output->{'__wakeup'} = $this->_wakeup_1;
        $output->{'__toString'} = $this->_toString_1;
        $output->{'__invoke'} = $this->_invoke_1;
        $output->{'__debugInfo'} = $this->_debugInfo_1;
        $output->{'__clone'} = $this->_clone_2;
        $output->{'files'} = $this->files;
        $output->{'this'} = $this->_this;
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
        $validator->validate($input, self::$_schema);

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
