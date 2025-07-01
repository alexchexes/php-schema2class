<?php

declare(strict_types=1);

namespace Ns\FallbackNamingPreserve;

class Foo
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
            'buildFromInput',
            'toArray',
            'validateInput',
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
            'buildFromInput' => [
                'type' => 'string',
            ],
            'toArray' => [
                'type' => 'string',
            ],
            'validateInput' => [
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
        ],
    ];

    /**
     * @var string
     */
    private string $_GLOBALS_1;

    /**
     * @var string
     */
    private string $GLOBALS_2;

    /**
     * @var string
     */
    private string $GLOBALS_1_1;

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
    private string $php_errormsg_1;

    /**
     * @var string
     */
    private string $http_response_header_1;

    /**
     * @var string
     */
    private string $argc_1;

    /**
     * @var string
     */
    private string $argv_1;

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
    private string $buildFromInput_1;

    /**
     * @var string
     */
    private string $toArray_1;

    /**
     * @var string
     */
    private string $validateInput_1;

    /**
     * @var string
     */
    private string $clone_1;

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
    private string $__get_2;

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
    private string $__clone_2;

    /**
     * @param string $_GLOBALS_1
     * @param string $GLOBALS_2
     * @param string $GLOBALS_1_1
     * @param string $_SERVER_1
     * @param string $_GET_1
     * @param string $_POST_1
     * @param string $_FILES_1
     * @param string $_REQUEST_1
     * @param string $_SESSION_1
     * @param string $_ENV_1
     * @param string $_COOKIE_1
     * @param string $php_errormsg_1
     * @param string $http_response_header_1
     * @param string $argc_1
     * @param string $argv_1
     * @param string $input
     * @param string $validate
     * @param string $obj
     * @param string $buildFromInput_1
     * @param string $toArray_1
     * @param string $validateInput_1
     * @param string $clone_1
     * @param string $__construct_1
     * @param string $__destruct_1
     * @param string $__get_2
     * @param string $__set_1
     * @param string $__call_1
     * @param string $__isset_1
     * @param string $__unset_1
     * @param string $__sleep_1
     * @param string $__wakeup_1
     * @param string $__toString_1
     * @param string $__invoke_1
     * @param string $__debugInfo_1
     * @param string $__clone_2
     */
    public function __construct(string $_GLOBALS_1, string $GLOBALS_2, string $GLOBALS_1_1, string $_SERVER_1, string $_GET_1, string $_POST_1, string $_FILES_1, string $_REQUEST_1, string $_SESSION_1, string $_ENV_1, string $_COOKIE_1, string $php_errormsg_1, string $http_response_header_1, string $argc_1, string $argv_1, string $input, string $validate, string $obj, string $buildFromInput_1, string $toArray_1, string $validateInput_1, string $clone_1, string $__construct_1, string $__destruct_1, string $__get_2, string $__set_1, string $__call_1, string $__isset_1, string $__unset_1, string $__sleep_1, string $__wakeup_1, string $__toString_1, string $__invoke_1, string $__debugInfo_1, string $__clone_2)
    {
        $this->_GLOBALS_1 = $_GLOBALS_1;
        $this->GLOBALS_2 = $GLOBALS_2;
        $this->GLOBALS_1_1 = $GLOBALS_1_1;
        $this->_SERVER_1 = $_SERVER_1;
        $this->_GET_1 = $_GET_1;
        $this->_POST_1 = $_POST_1;
        $this->_FILES_1 = $_FILES_1;
        $this->_REQUEST_1 = $_REQUEST_1;
        $this->_SESSION_1 = $_SESSION_1;
        $this->_ENV_1 = $_ENV_1;
        $this->_COOKIE_1 = $_COOKIE_1;
        $this->php_errormsg_1 = $php_errormsg_1;
        $this->http_response_header_1 = $http_response_header_1;
        $this->argc_1 = $argc_1;
        $this->argv_1 = $argv_1;
        $this->input = $input;
        $this->validate = $validate;
        $this->obj = $obj;
        $this->buildFromInput_1 = $buildFromInput_1;
        $this->toArray_1 = $toArray_1;
        $this->validateInput_1 = $validateInput_1;
        $this->clone_1 = $clone_1;
        $this->__construct_1 = $__construct_1;
        $this->__destruct_1 = $__destruct_1;
        $this->__get_2 = $__get_2;
        $this->__set_1 = $__set_1;
        $this->__call_1 = $__call_1;
        $this->__isset_1 = $__isset_1;
        $this->__unset_1 = $__unset_1;
        $this->__sleep_1 = $__sleep_1;
        $this->__wakeup_1 = $__wakeup_1;
        $this->__toString_1 = $__toString_1;
        $this->__invoke_1 = $__invoke_1;
        $this->__debugInfo_1 = $__debugInfo_1;
        $this->__clone_2 = $__clone_2;
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
        return $this->GLOBALS_2;
    }

    /**
     * @return string
     */
    public function getGLOBALS11() : string
    {
        return $this->GLOBALS_1_1;
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
    public function getPhpErrormsg1() : string
    {
        return $this->php_errormsg_1;
    }

    /**
     * @return string
     */
    public function getHttpResponseHeader1() : string
    {
        return $this->http_response_header_1;
    }

    /**
     * @return string
     */
    public function getArgc1() : string
    {
        return $this->argc_1;
    }

    /**
     * @return string
     */
    public function getArgv1() : string
    {
        return $this->argv_1;
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
    public function getBuildFromInput1() : string
    {
        return $this->buildFromInput_1;
    }

    /**
     * @return string
     */
    public function getToArray1() : string
    {
        return $this->toArray_1;
    }

    /**
     * @return string
     */
    public function getValidateInput1() : string
    {
        return $this->validateInput_1;
    }

    /**
     * @return string
     */
    public function getClone1() : string
    {
        return $this->clone_1;
    }

    /**
     * @return string
     */
    public function getConstruct1() : string
    {
        return $this->__construct_1;
    }

    /**
     * @return string
     */
    public function getDestruct1() : string
    {
        return $this->__destruct_1;
    }

    /**
     * @return string
     */
    public function getGet2() : string
    {
        return $this->__get_2;
    }

    /**
     * @return string
     */
    public function getSet1() : string
    {
        return $this->__set_1;
    }

    /**
     * @return string
     */
    public function getCall1() : string
    {
        return $this->__call_1;
    }

    /**
     * @return string
     */
    public function getIsset1() : string
    {
        return $this->__isset_1;
    }

    /**
     * @return string
     */
    public function getUnset1() : string
    {
        return $this->__unset_1;
    }

    /**
     * @return string
     */
    public function getSleep1() : string
    {
        return $this->__sleep_1;
    }

    /**
     * @return string
     */
    public function getWakeup1() : string
    {
        return $this->__wakeup_1;
    }

    /**
     * @return string
     */
    public function getToString1() : string
    {
        return $this->__toString_1;
    }

    /**
     * @return string
     */
    public function getInvoke1() : string
    {
        return $this->__invoke_1;
    }

    /**
     * @return string
     */
    public function getDebugInfo1() : string
    {
        return $this->__debugInfo_1;
    }

    /**
     * @return string
     */
    public function getClone2() : string
    {
        return $this->__clone_2;
    }

    /**
     * @param string $_GLOBALS_1
     * @return self
     */
    public function withGLOBALS1(string $_GLOBALS_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($_GLOBALS_1, self::$schema['properties']['_GLOBALS']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->_GLOBALS_1 = $_GLOBALS_1;

        return $clone;
    }

    /**
     * @param string $GLOBALS_2
     * @return self
     */
    public function withGLOBALS2(string $GLOBALS_2) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($GLOBALS_2, self::$schema['properties']['GLOBALS']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->GLOBALS_2 = $GLOBALS_2;

        return $clone;
    }

    /**
     * @param string $GLOBALS_1_1
     * @return self
     */
    public function withGLOBALS11(string $GLOBALS_1_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($GLOBALS_1_1, self::$schema['properties']['GLOBALS_1']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->GLOBALS_1_1 = $GLOBALS_1_1;

        return $clone;
    }

    /**
     * @param string $_SERVER_1
     * @return self
     */
    public function withSERVER1(string $_SERVER_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($_SERVER_1, self::$schema['properties']['_SERVER']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->_SERVER_1 = $_SERVER_1;

        return $clone;
    }

    /**
     * @param string $_GET_1
     * @return self
     */
    public function withGET1(string $_GET_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($_GET_1, self::$schema['properties']['_GET']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->_GET_1 = $_GET_1;

        return $clone;
    }

    /**
     * @param string $_POST_1
     * @return self
     */
    public function withPOST1(string $_POST_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($_POST_1, self::$schema['properties']['_POST']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->_POST_1 = $_POST_1;

        return $clone;
    }

    /**
     * @param string $_FILES_1
     * @return self
     */
    public function withFILES1(string $_FILES_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($_FILES_1, self::$schema['properties']['_FILES']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->_FILES_1 = $_FILES_1;

        return $clone;
    }

    /**
     * @param string $_REQUEST_1
     * @return self
     */
    public function withREQUEST1(string $_REQUEST_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($_REQUEST_1, self::$schema['properties']['_REQUEST']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->_REQUEST_1 = $_REQUEST_1;

        return $clone;
    }

    /**
     * @param string $_SESSION_1
     * @return self
     */
    public function withSESSION1(string $_SESSION_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($_SESSION_1, self::$schema['properties']['_SESSION']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->_SESSION_1 = $_SESSION_1;

        return $clone;
    }

    /**
     * @param string $_ENV_1
     * @return self
     */
    public function withENV1(string $_ENV_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($_ENV_1, self::$schema['properties']['_ENV']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->_ENV_1 = $_ENV_1;

        return $clone;
    }

    /**
     * @param string $_COOKIE_1
     * @return self
     */
    public function withCOOKIE1(string $_COOKIE_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($_COOKIE_1, self::$schema['properties']['_COOKIE']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->_COOKIE_1 = $_COOKIE_1;

        return $clone;
    }

    /**
     * @param string $php_errormsg_1
     * @return self
     */
    public function withPhpErrormsg1(string $php_errormsg_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($php_errormsg_1, self::$schema['properties']['php_errormsg']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->php_errormsg_1 = $php_errormsg_1;

        return $clone;
    }

    /**
     * @param string $http_response_header_1
     * @return self
     */
    public function withHttpResponseHeader1(string $http_response_header_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($http_response_header_1, self::$schema['properties']['http_response_header']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->http_response_header_1 = $http_response_header_1;

        return $clone;
    }

    /**
     * @param string $argc_1
     * @return self
     */
    public function withArgc1(string $argc_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($argc_1, self::$schema['properties']['argc']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->argc_1 = $argc_1;

        return $clone;
    }

    /**
     * @param string $argv_1
     * @return self
     */
    public function withArgv1(string $argv_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($argv_1, self::$schema['properties']['argv']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->argv_1 = $argv_1;

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
     * @param string $buildFromInput_1
     * @return self
     */
    public function withBuildFromInput1(string $buildFromInput_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($buildFromInput_1, self::$schema['properties']['buildFromInput']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->buildFromInput_1 = $buildFromInput_1;

        return $clone;
    }

    /**
     * @param string $toArray_1
     * @return self
     */
    public function withToArray1(string $toArray_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($toArray_1, self::$schema['properties']['toArray']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->toArray_1 = $toArray_1;

        return $clone;
    }

    /**
     * @param string $validateInput_1
     * @return self
     */
    public function withValidateInput1(string $validateInput_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($validateInput_1, self::$schema['properties']['validateInput']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->validateInput_1 = $validateInput_1;

        return $clone;
    }

    /**
     * @param string $clone_1
     * @return self
     */
    public function withClone1(string $clone_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($clone_1, self::$schema['properties']['clone']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->clone_1 = $clone_1;

        return $clone;
    }

    /**
     * @param string $__construct_1
     * @return self
     */
    public function withConstruct1(string $__construct_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($__construct_1, self::$schema['properties']['__construct']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->__construct_1 = $__construct_1;

        return $clone;
    }

    /**
     * @param string $__destruct_1
     * @return self
     */
    public function withDestruct1(string $__destruct_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($__destruct_1, self::$schema['properties']['__destruct']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->__destruct_1 = $__destruct_1;

        return $clone;
    }

    /**
     * @param string $__get_2
     * @return self
     */
    public function withGet2(string $__get_2) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($__get_2, self::$schema['properties']['__get']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->__get_2 = $__get_2;

        return $clone;
    }

    /**
     * @param string $__set_1
     * @return self
     */
    public function withSet1(string $__set_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($__set_1, self::$schema['properties']['__set']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->__set_1 = $__set_1;

        return $clone;
    }

    /**
     * @param string $__call_1
     * @return self
     */
    public function withCall1(string $__call_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($__call_1, self::$schema['properties']['__call']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->__call_1 = $__call_1;

        return $clone;
    }

    /**
     * @param string $__isset_1
     * @return self
     */
    public function withIsset1(string $__isset_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($__isset_1, self::$schema['properties']['__isset']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->__isset_1 = $__isset_1;

        return $clone;
    }

    /**
     * @param string $__unset_1
     * @return self
     */
    public function withUnset1(string $__unset_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($__unset_1, self::$schema['properties']['__unset']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->__unset_1 = $__unset_1;

        return $clone;
    }

    /**
     * @param string $__sleep_1
     * @return self
     */
    public function withSleep1(string $__sleep_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($__sleep_1, self::$schema['properties']['__sleep']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->__sleep_1 = $__sleep_1;

        return $clone;
    }

    /**
     * @param string $__wakeup_1
     * @return self
     */
    public function withWakeup1(string $__wakeup_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($__wakeup_1, self::$schema['properties']['__wakeup']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->__wakeup_1 = $__wakeup_1;

        return $clone;
    }

    /**
     * @param string $__toString_1
     * @return self
     */
    public function withToString1(string $__toString_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($__toString_1, self::$schema['properties']['__toString']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->__toString_1 = $__toString_1;

        return $clone;
    }

    /**
     * @param string $__invoke_1
     * @return self
     */
    public function withInvoke1(string $__invoke_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($__invoke_1, self::$schema['properties']['__invoke']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->__invoke_1 = $__invoke_1;

        return $clone;
    }

    /**
     * @param string $__debugInfo_1
     * @return self
     */
    public function withDebugInfo1(string $__debugInfo_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($__debugInfo_1, self::$schema['properties']['__debugInfo']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->__debugInfo_1 = $__debugInfo_1;

        return $clone;
    }

    /**
     * @param string $__clone_2
     * @return self
     */
    public function withClone2(string $__clone_2) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($__clone_2, self::$schema['properties']['__clone']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->__clone_2 = $__clone_2;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $_input Input data
     * @param bool $_validate Set this to false to skip validation; use at own risk
     * @return Foo Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $_input, bool $_validate = true) : Foo
    {
        $_input = is_array($_input) ? \JsonSchema\Validator::arrayToObjectRecursive($_input) : $_input;
        if ($_validate) {
            static::validateInput($_input);
        }

        $validate = $_validate;
        $_GLOBALS_1 = $_input->{'_GLOBALS'};
        $GLOBALS_2 = $_input->{'GLOBALS'};
        $GLOBALS_1_1 = $_input->{'GLOBALS_1'};
        $_SERVER_1 = $_input->{'_SERVER'};
        $_GET_1 = $_input->{'_GET'};
        $_POST_1 = $_input->{'_POST'};
        $_FILES_1 = $_input->{'_FILES'};
        $_REQUEST_1 = $_input->{'_REQUEST'};
        $_SESSION_1 = $_input->{'_SESSION'};
        $_ENV_1 = $_input->{'_ENV'};
        $_COOKIE_1 = $_input->{'_COOKIE'};
        $php_errormsg_1 = $_input->{'php_errormsg'};
        $http_response_header_1 = $_input->{'http_response_header'};
        $argc_1 = $_input->{'argc'};
        $argv_1 = $_input->{'argv'};
        $input = $_input->{'input'};
        $validate = $_input->{'validate'};
        $obj = $_input->{'obj'};
        $buildFromInput_1 = $_input->{'buildFromInput'};
        $toArray_1 = $_input->{'toArray'};
        $validateInput_1 = $_input->{'validateInput'};
        $clone_1 = $_input->{'clone'};
        $__construct_1 = $_input->{'__construct'};
        $__destruct_1 = $_input->{'__destruct'};
        $__get_2 = $_input->{'__get'};
        $__set_1 = $_input->{'__set'};
        $__call_1 = $_input->{'__call'};
        $__isset_1 = $_input->{'__isset'};
        $__unset_1 = $_input->{'__unset'};
        $__sleep_1 = $_input->{'__sleep'};
        $__wakeup_1 = $_input->{'__wakeup'};
        $__toString_1 = $_input->{'__toString'};
        $__invoke_1 = $_input->{'__invoke'};
        $__debugInfo_1 = $_input->{'__debugInfo'};
        $__clone_2 = $_input->{'__clone'};

        $_obj = new self($_GLOBALS_1, $GLOBALS_2, $GLOBALS_1_1, $_SERVER_1, $_GET_1, $_POST_1, $_FILES_1, $_REQUEST_1, $_SESSION_1, $_ENV_1, $_COOKIE_1, $php_errormsg_1, $http_response_header_1, $argc_1, $argv_1, $input, $validate, $obj, $buildFromInput_1, $toArray_1, $validateInput_1, $clone_1, $__construct_1, $__destruct_1, $__get_2, $__set_1, $__call_1, $__isset_1, $__unset_1, $__sleep_1, $__wakeup_1, $__toString_1, $__invoke_1, $__debugInfo_1, $__clone_2);

        return $_obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray() : array
    {
        $output = [];
        $output['_GLOBALS'] = $this->_GLOBALS_1;
        $output['GLOBALS'] = $this->GLOBALS_2;
        $output['GLOBALS_1'] = $this->GLOBALS_1_1;
        $output['_SERVER'] = $this->_SERVER_1;
        $output['_GET'] = $this->_GET_1;
        $output['_POST'] = $this->_POST_1;
        $output['_FILES'] = $this->_FILES_1;
        $output['_REQUEST'] = $this->_REQUEST_1;
        $output['_SESSION'] = $this->_SESSION_1;
        $output['_ENV'] = $this->_ENV_1;
        $output['_COOKIE'] = $this->_COOKIE_1;
        $output['php_errormsg'] = $this->php_errormsg_1;
        $output['http_response_header'] = $this->http_response_header_1;
        $output['argc'] = $this->argc_1;
        $output['argv'] = $this->argv_1;
        $output['input'] = $this->input;
        $output['validate'] = $this->validate;
        $output['obj'] = $this->obj;
        $output['buildFromInput'] = $this->buildFromInput_1;
        $output['toArray'] = $this->toArray_1;
        $output['validateInput'] = $this->validateInput_1;
        $output['clone'] = $this->clone_1;
        $output['__construct'] = $this->__construct_1;
        $output['__destruct'] = $this->__destruct_1;
        $output['__get'] = $this->__get_2;
        $output['__set'] = $this->__set_1;
        $output['__call'] = $this->__call_1;
        $output['__isset'] = $this->__isset_1;
        $output['__unset'] = $this->__unset_1;
        $output['__sleep'] = $this->__sleep_1;
        $output['__wakeup'] = $this->__wakeup_1;
        $output['__toString'] = $this->__toString_1;
        $output['__invoke'] = $this->__invoke_1;
        $output['__debugInfo'] = $this->__debugInfo_1;
        $output['__clone'] = $this->__clone_2;

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
