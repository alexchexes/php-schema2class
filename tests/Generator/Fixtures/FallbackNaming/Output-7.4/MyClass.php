<?php

declare(strict_types=1);

namespace Ns\FallbackNaming_7_4;

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
    private static array $_defaults = [
        '_defaults' => 'foo',
    ];

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
    private string $materializeDefaults;

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
    private string $_buildFromInput_1;

    /**
     * @var string
     */
    private string $_toArray_1;

    /**
     * @var string
     */
    private string $_validateInput_1;

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
     * @param string $validate
     * @param string $obj
     * @param string $materializeDefaults
     * @param string $includeDefaults
     * @param string $_buildFromInput_1
     * @param string $_toArray_1
     * @param string $_validateInput_1
     * @param string $_schema
     * @param string $_defaults_1
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
     */
    public function __construct(string $_GLOBALS_1, string $_GLOBALS_2, string $_GLOBALS1_1, string $_SERVER_1, string $_GET_1, string $_POST_1, string $_FILES_1, string $_REQUEST_1, string $_SESSION_1, string $_ENV_1, string $_COOKIE_1, string $_phpErrormsg, string $_httpResponseHeader, string $_argc, string $_argv, string $input, string $validate, string $obj, string $materializeDefaults, string $includeDefaults, string $_buildFromInput_1, string $_toArray_1, string $_validateInput_1, string $_schema, string $_defaults_1, string $_clone_1, string $_construct_1, string $_destruct_1, string $_get_2, string $_set_1, string $_call_1, string $_isset_1, string $_unset_1, string $_sleep_1, string $_wakeup_1, string $_toString_1, string $_invoke_1, string $_debugInfo_1, string $_clone_2, string $files)
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
        $this->validate = $validate;
        $this->obj = $obj;
        $this->materializeDefaults = $materializeDefaults;
        $this->includeDefaults = $includeDefaults;
        $this->_buildFromInput_1 = $_buildFromInput_1;
        $this->_toArray_1 = $_toArray_1;
        $this->_validateInput_1 = $_validateInput_1;
        $this->_schema = $_schema;
        $this->_defaults_1 = $_defaults_1;
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
    }

    /**
     * @return string
     */
    public function getGLOBALS1() : string
    {
        return $this->_GLOBALS_1;
    }

    /**
     * @return string
     */
    public function getGLOBALS2() : string
    {
        return $this->_GLOBALS_2;
    }

    /**
     * @return string
     */
    public function getGLOBALS11() : string
    {
        return $this->_GLOBALS1_1;
    }

    /**
     * @return string
     */
    public function getSERVER1() : string
    {
        return $this->_SERVER_1;
    }

    /**
     * @return string
     */
    public function getGET1() : string
    {
        return $this->_GET_1;
    }

    /**
     * @return string
     */
    public function getPOST1() : string
    {
        return $this->_POST_1;
    }

    /**
     * @return string
     */
    public function getFILES1() : string
    {
        return $this->_FILES_1;
    }

    /**
     * @return string
     */
    public function getREQUEST1() : string
    {
        return $this->_REQUEST_1;
    }

    /**
     * @return string
     */
    public function getSESSION1() : string
    {
        return $this->_SESSION_1;
    }

    /**
     * @return string
     */
    public function getENV1() : string
    {
        return $this->_ENV_1;
    }

    /**
     * @return string
     */
    public function getCOOKIE1() : string
    {
        return $this->_COOKIE_1;
    }

    /**
     * @return string
     */
    public function getPhpErrormsg() : string
    {
        return $this->_phpErrormsg;
    }

    /**
     * @return string
     */
    public function getHttpResponseHeader() : string
    {
        return $this->_httpResponseHeader;
    }

    /**
     * @return string
     */
    public function getArgc() : string
    {
        return $this->_argc;
    }

    /**
     * @return string
     */
    public function getArgv() : string
    {
        return $this->_argv;
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
    public function getMaterializeDefaults() : string
    {
        return $this->materializeDefaults;
    }

    /**
     * @return string
     */
    public function getIncludeDefaults() : string
    {
        return $this->includeDefaults;
    }

    /**
     * @return MyClassTestObj|null
     */
    public function getTestObj() : ?MyClassTestObj
    {
        return $this->testObj ?? null;
    }

    /**
     * @return string
     */
    public function getBuildFromInput1() : string
    {
        return $this->_buildFromInput_1;
    }

    /**
     * @return string
     */
    public function getToArray1() : string
    {
        return $this->_toArray_1;
    }

    /**
     * @return string
     */
    public function getValidateInput1() : string
    {
        return $this->_validateInput_1;
    }

    /**
     * @return string
     */
    public function getSchema() : string
    {
        return $this->_schema;
    }

    /**
     * @return string
     */
    public function getDefaults1() : string
    {
        return $this->_defaults_1;
    }

    /**
     * @return string
     */
    public function getClone1() : string
    {
        return $this->_clone_1;
    }

    /**
     * @return string
     */
    public function getConstruct1() : string
    {
        return $this->_construct_1;
    }

    /**
     * @return string
     */
    public function getDestruct1() : string
    {
        return $this->_destruct_1;
    }

    /**
     * @return string
     */
    public function getGet2() : string
    {
        return $this->_get_2;
    }

    /**
     * @return string
     */
    public function getSet1() : string
    {
        return $this->_set_1;
    }

    /**
     * @return string
     */
    public function getCall1() : string
    {
        return $this->_call_1;
    }

    /**
     * @return string
     */
    public function getIsset1() : string
    {
        return $this->_isset_1;
    }

    /**
     * @return string
     */
    public function getUnset1() : string
    {
        return $this->_unset_1;
    }

    /**
     * @return string
     */
    public function getSleep1() : string
    {
        return $this->_sleep_1;
    }

    /**
     * @return string
     */
    public function getWakeup1() : string
    {
        return $this->_wakeup_1;
    }

    /**
     * @return string
     */
    public function getToString1() : string
    {
        return $this->_toString_1;
    }

    /**
     * @return string
     */
    public function getInvoke1() : string
    {
        return $this->_invoke_1;
    }

    /**
     * @return string
     */
    public function getDebugInfo1() : string
    {
        return $this->_debugInfo_1;
    }

    /**
     * @return string
     */
    public function getClone2() : string
    {
        return $this->_clone_2;
    }

    /**
     * @return string
     */
    public function getFiles() : string
    {
        return $this->files;
    }

    /**
     * @param string $_GLOBALS_1
     * @return self
     * @param bool $validate
     */
    public function withGLOBALS1(string $_GLOBALS_1, bool $validate = true) : self
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
    public function withGLOBALS2(string $_GLOBALS_2, bool $validate = true) : self
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
     * @param string $_GLOBALS1_1
     * @return self
     * @param bool $validate
     */
    public function withGLOBALS11(string $_GLOBALS1_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_GLOBALS1_1, self::$schema['properties']['GLOBALS_1']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_GLOBALS1_1 = $_GLOBALS1_1;

        return $clone;
    }

    /**
     * @param string $_SERVER_1
     * @return self
     * @param bool $validate
     */
    public function withSERVER1(string $_SERVER_1, bool $validate = true) : self
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
    public function withGET1(string $_GET_1, bool $validate = true) : self
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
    public function withPOST1(string $_POST_1, bool $validate = true) : self
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
    public function withFILES1(string $_FILES_1, bool $validate = true) : self
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
    public function withREQUEST1(string $_REQUEST_1, bool $validate = true) : self
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
    public function withSESSION1(string $_SESSION_1, bool $validate = true) : self
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
    public function withENV1(string $_ENV_1, bool $validate = true) : self
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
    public function withCOOKIE1(string $_COOKIE_1, bool $validate = true) : self
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
     * @param string $_phpErrormsg
     * @return self
     * @param bool $validate
     */
    public function withPhpErrormsg(string $_phpErrormsg, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_phpErrormsg, self::$schema['properties']['php_errormsg']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_phpErrormsg = $_phpErrormsg;

        return $clone;
    }

    /**
     * @param string $_httpResponseHeader
     * @return self
     * @param bool $validate
     */
    public function withHttpResponseHeader(string $_httpResponseHeader, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_httpResponseHeader, self::$schema['properties']['http_response_header']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_httpResponseHeader = $_httpResponseHeader;

        return $clone;
    }

    /**
     * @param string $_argc
     * @return self
     * @param bool $validate
     */
    public function withArgc(string $_argc, bool $validate = true) : self
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
    public function withArgv(string $_argv, bool $validate = true) : self
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
    public function withInput(string $input, bool $validate = true) : self
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
    public function withValidate(bool $validate = true) : self
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
    public function withObj(string $obj, bool $validate = true) : self
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
    public function withMaterializeDefaults(string $materializeDefaults, bool $validate = true) : self
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
    public function withIncludeDefaults(string $includeDefaults, bool $validate = true) : self
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
    public function withTestObj(MyClassTestObj $testObj) : self
    {
        $clone = clone $this;
        $clone->testObj = $testObj;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutTestObj() : self
    {
        $clone = clone $this;
        unset($clone->testObj);

        return $clone;
    }

    /**
     * @param string $_buildFromInput_1
     * @return self
     * @param bool $validate
     */
    public function withBuildFromInput1(string $_buildFromInput_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_buildFromInput_1, self::$schema['properties']['buildFromInput']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_buildFromInput_1 = $_buildFromInput_1;

        return $clone;
    }

    /**
     * @param string $_toArray_1
     * @return self
     * @param bool $validate
     */
    public function withToArray1(string $_toArray_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_toArray_1, self::$schema['properties']['toArray']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_toArray_1 = $_toArray_1;

        return $clone;
    }

    /**
     * @param string $_validateInput_1
     * @return self
     * @param bool $validate
     */
    public function withValidateInput1(string $_validateInput_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_validateInput_1, self::$schema['properties']['validateInput']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_validateInput_1 = $_validateInput_1;

        return $clone;
    }

    /**
     * @param string $_schema
     * @return self
     * @param bool $validate
     */
    public function withSchema(string $_schema, bool $validate = true) : self
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
    public function withDefaults1(string $_defaults_1, bool $validate = true) : self
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
     * @param string $_clone_1
     * @return self
     * @param bool $validate
     */
    public function withClone1(string $_clone_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_clone_1, self::$schema['properties']['clone']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_clone_1 = $_clone_1;

        return $clone;
    }

    /**
     * @param string $_construct_1
     * @return self
     * @param bool $validate
     */
    public function withConstruct1(string $_construct_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_construct_1, self::$schema['properties']['__construct']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_construct_1 = $_construct_1;

        return $clone;
    }

    /**
     * @param string $_destruct_1
     * @return self
     * @param bool $validate
     */
    public function withDestruct1(string $_destruct_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_destruct_1, self::$schema['properties']['__destruct']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_destruct_1 = $_destruct_1;

        return $clone;
    }

    /**
     * @param string $_get_2
     * @return self
     * @param bool $validate
     */
    public function withGet2(string $_get_2, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_get_2, self::$schema['properties']['__get']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_get_2 = $_get_2;

        return $clone;
    }

    /**
     * @param string $_set_1
     * @return self
     * @param bool $validate
     */
    public function withSet1(string $_set_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_set_1, self::$schema['properties']['__set']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_set_1 = $_set_1;

        return $clone;
    }

    /**
     * @param string $_call_1
     * @return self
     * @param bool $validate
     */
    public function withCall1(string $_call_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_call_1, self::$schema['properties']['__call']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_call_1 = $_call_1;

        return $clone;
    }

    /**
     * @param string $_isset_1
     * @return self
     * @param bool $validate
     */
    public function withIsset1(string $_isset_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_isset_1, self::$schema['properties']['__isset']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_isset_1 = $_isset_1;

        return $clone;
    }

    /**
     * @param string $_unset_1
     * @return self
     * @param bool $validate
     */
    public function withUnset1(string $_unset_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_unset_1, self::$schema['properties']['__unset']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_unset_1 = $_unset_1;

        return $clone;
    }

    /**
     * @param string $_sleep_1
     * @return self
     * @param bool $validate
     */
    public function withSleep1(string $_sleep_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_sleep_1, self::$schema['properties']['__sleep']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_sleep_1 = $_sleep_1;

        return $clone;
    }

    /**
     * @param string $_wakeup_1
     * @return self
     * @param bool $validate
     */
    public function withWakeup1(string $_wakeup_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_wakeup_1, self::$schema['properties']['__wakeup']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_wakeup_1 = $_wakeup_1;

        return $clone;
    }

    /**
     * @param string $_toString_1
     * @return self
     * @param bool $validate
     */
    public function withToString1(string $_toString_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_toString_1, self::$schema['properties']['__toString']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_toString_1 = $_toString_1;

        return $clone;
    }

    /**
     * @param string $_invoke_1
     * @return self
     * @param bool $validate
     */
    public function withInvoke1(string $_invoke_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_invoke_1, self::$schema['properties']['__invoke']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_invoke_1 = $_invoke_1;

        return $clone;
    }

    /**
     * @param string $_debugInfo_1
     * @return self
     * @param bool $validate
     */
    public function withDebugInfo1(string $_debugInfo_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_debugInfo_1, self::$schema['properties']['__debugInfo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_debugInfo_1 = $_debugInfo_1;

        return $clone;
    }

    /**
     * @param string $_clone_2
     * @return self
     * @param bool $validate
     */
    public function withClone2(string $_clone_2, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_clone_2, self::$schema['properties']['__clone']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_clone_2 = $_clone_2;

        return $clone;
    }

    /**
     * @param string $files
     * @return self
     * @param bool $validate
     */
    public function withFiles(string $files, bool $validate = true) : self
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
    public static function buildFromInput($_input, bool $_validate = true, bool $_materializeDefaults = false) : MyClass
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
        $_GLOBALS1_1 = $_input->{'GLOBALS_1'};
        $_SERVER_1 = $_input->{'_SERVER'};
        $_GET_1 = $_input->{'_GET'};
        $_POST_1 = $_input->{'_POST'};
        $_FILES_1 = $_input->{'_FILES'};
        $_REQUEST_1 = $_input->{'_REQUEST'};
        $_SESSION_1 = $_input->{'_SESSION'};
        $_ENV_1 = $_input->{'_ENV'};
        $_COOKIE_1 = $_input->{'_COOKIE'};
        $_phpErrormsg = $_input->{'php_errormsg'};
        $_httpResponseHeader = $_input->{'http_response_header'};
        $_argc = $_input->{'argc'};
        $_argv = $_input->{'argv'};
        $input = $_input->{'input'};
        $validate = $_input->{'validate'};
        $obj = $_input->{'obj'};
        $materializeDefaults = $_input->{'materializeDefaults'};
        $includeDefaults = $_input->{'includeDefaults'};
        $testObj = isset($_input->{'testObj'}) ? MyClassTestObj::buildFromInput($_input->{'testObj'}, $validate) : null;
        $_buildFromInput_1 = $_input->{'buildFromInput'};
        $_toArray_1 = $_input->{'toArray'};
        $_validateInput_1 = $_input->{'validateInput'};
        $_schema = $_input->{'schema'};
        $_defaults_1 = $_input->{'_defaults'};
        $_clone_1 = $_input->{'clone'};
        $_construct_1 = $_input->{'__construct'};
        $_destruct_1 = $_input->{'__destruct'};
        $_get_2 = $_input->{'__get'};
        $_set_1 = $_input->{'__set'};
        $_call_1 = $_input->{'__call'};
        $_isset_1 = $_input->{'__isset'};
        $_unset_1 = $_input->{'__unset'};
        $_sleep_1 = $_input->{'__sleep'};
        $_wakeup_1 = $_input->{'__wakeup'};
        $_toString_1 = $_input->{'__toString'};
        $_invoke_1 = $_input->{'__invoke'};
        $_debugInfo_1 = $_input->{'__debugInfo'};
        $_clone_2 = $_input->{'__clone'};
        $files = $_input->{'files'};

        $_obj = new self($_GLOBALS_1, $_GLOBALS_2, $_GLOBALS1_1, $_SERVER_1, $_GET_1, $_POST_1, $_FILES_1, $_REQUEST_1, $_SESSION_1, $_ENV_1, $_COOKIE_1, $_phpErrormsg, $_httpResponseHeader, $_argc, $_argv, $input, $validate, $obj, $materializeDefaults, $includeDefaults, $_buildFromInput_1, $_toArray_1, $_validateInput_1, $_schema, $_defaults_1, $_clone_1, $_construct_1, $_destruct_1, $_get_2, $_set_1, $_call_1, $_isset_1, $_unset_1, $_sleep_1, $_wakeup_1, $_toString_1, $_invoke_1, $_debugInfo_1, $_clone_2, $files);
        $_obj->testObj = $testObj;
        return $_obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return array Converted array
     */
    public function toArray(bool $includeDefaults = false) : array
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
        $output['validate'] = $this->validate;
        $output['obj'] = $this->obj;
        $output['materializeDefaults'] = $this->materializeDefaults;
        $output['includeDefaults'] = $this->includeDefaults;
        if (isset($this->testObj)) {
            $output['testObj'] = ($this->testObj)->toArray();
        }
        $output['buildFromInput'] = $this->_buildFromInput_1;
        $output['toArray'] = $this->_toArray_1;
        $output['validateInput'] = $this->_validateInput_1;
        $output['schema'] = $this->_schema;
        $output['_defaults'] = $this->_defaults_1;
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
    public static function validateInput($input, bool $return = false) : bool
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
    }
}
