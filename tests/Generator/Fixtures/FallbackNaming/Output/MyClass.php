<?php

declare(strict_types=1);

namespace Ns\FallbackNaming;

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
            'files' => [
                'type' => 'string',
            ],
        ],
    ];

    /**
     * @var string
     */
    private string $GLOBALS_2;

    /**
     * @var string
     */
    private string $GLOBALS_3;

    /**
     * @var string
     */
    private string $GLOBALS1_1;

    /**
     * @var string
     */
    private string $SERVER_1;

    /**
     * @var string
     */
    private string $GET_1;

    /**
     * @var string
     */
    private string $POST_1;

    /**
     * @var string
     */
    private string $FILES_1;

    /**
     * @var string
     */
    private string $REQUEST_1;

    /**
     * @var string
     */
    private string $SESSION_1;

    /**
     * @var string
     */
    private string $ENV_1;

    /**
     * @var string
     */
    private string $COOKIE_1;

    /**
     * @var string
     */
    private string $phpErrormsg_1;

    /**
     * @var string
     */
    private string $httpResponseHeader_1;

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
    private string $construct_1;

    /**
     * @var string
     */
    private string $destruct_1;

    /**
     * @var string
     */
    private string $get_2;

    /**
     * @var string
     */
    private string $set_1;

    /**
     * @var string
     */
    private string $call_1;

    /**
     * @var string
     */
    private string $isset_1;

    /**
     * @var string
     */
    private string $unset_1;

    /**
     * @var string
     */
    private string $sleep_1;

    /**
     * @var string
     */
    private string $wakeup_1;

    /**
     * @var string
     */
    private string $toString_1;

    /**
     * @var string
     */
    private string $invoke_1;

    /**
     * @var string
     */
    private string $debugInfo_1;

    /**
     * @var string
     */
    private string $clone_2;

    /**
     * @var string
     */
    private string $files;

    /**
     * @param string $GLOBALS_2
     * @param string $GLOBALS_3
     * @param string $GLOBALS1_1
     * @param string $SERVER_1
     * @param string $GET_1
     * @param string $POST_1
     * @param string $FILES_1
     * @param string $REQUEST_1
     * @param string $SESSION_1
     * @param string $ENV_1
     * @param string $COOKIE_1
     * @param string $phpErrormsg_1
     * @param string $httpResponseHeader_1
     * @param string $argc_1
     * @param string $argv_1
     * @param string $input
     * @param string $validate
     * @param string $obj
     * @param string $buildFromInput_1
     * @param string $toArray_1
     * @param string $validateInput_1
     * @param string $clone_1
     * @param string $construct_1
     * @param string $destruct_1
     * @param string $get_2
     * @param string $set_1
     * @param string $call_1
     * @param string $isset_1
     * @param string $unset_1
     * @param string $sleep_1
     * @param string $wakeup_1
     * @param string $toString_1
     * @param string $invoke_1
     * @param string $debugInfo_1
     * @param string $clone_2
     * @param string $files
     */
    public function __construct(string $GLOBALS_2, string $GLOBALS_3, string $GLOBALS1_1, string $SERVER_1, string $GET_1, string $POST_1, string $FILES_1, string $REQUEST_1, string $SESSION_1, string $ENV_1, string $COOKIE_1, string $phpErrormsg_1, string $httpResponseHeader_1, string $argc_1, string $argv_1, string $input, string $validate, string $obj, string $buildFromInput_1, string $toArray_1, string $validateInput_1, string $clone_1, string $construct_1, string $destruct_1, string $get_2, string $set_1, string $call_1, string $isset_1, string $unset_1, string $sleep_1, string $wakeup_1, string $toString_1, string $invoke_1, string $debugInfo_1, string $clone_2, string $files)
    {
        $this->GLOBALS_2 = $GLOBALS_2;
        $this->GLOBALS_3 = $GLOBALS_3;
        $this->GLOBALS1_1 = $GLOBALS1_1;
        $this->SERVER_1 = $SERVER_1;
        $this->GET_1 = $GET_1;
        $this->POST_1 = $POST_1;
        $this->FILES_1 = $FILES_1;
        $this->REQUEST_1 = $REQUEST_1;
        $this->SESSION_1 = $SESSION_1;
        $this->ENV_1 = $ENV_1;
        $this->COOKIE_1 = $COOKIE_1;
        $this->phpErrormsg_1 = $phpErrormsg_1;
        $this->httpResponseHeader_1 = $httpResponseHeader_1;
        $this->argc_1 = $argc_1;
        $this->argv_1 = $argv_1;
        $this->input = $input;
        $this->validate = $validate;
        $this->obj = $obj;
        $this->buildFromInput_1 = $buildFromInput_1;
        $this->toArray_1 = $toArray_1;
        $this->validateInput_1 = $validateInput_1;
        $this->clone_1 = $clone_1;
        $this->construct_1 = $construct_1;
        $this->destruct_1 = $destruct_1;
        $this->get_2 = $get_2;
        $this->set_1 = $set_1;
        $this->call_1 = $call_1;
        $this->isset_1 = $isset_1;
        $this->unset_1 = $unset_1;
        $this->sleep_1 = $sleep_1;
        $this->wakeup_1 = $wakeup_1;
        $this->toString_1 = $toString_1;
        $this->invoke_1 = $invoke_1;
        $this->debugInfo_1 = $debugInfo_1;
        $this->clone_2 = $clone_2;
        $this->files = $files;
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
    public function getGLOBALS3() : string
    {
        return $this->GLOBALS_3;
    }

    /**
     * @return string
     */
    public function getGLOBALS11() : string
    {
        return $this->GLOBALS1_1;
    }

    /**
     * @return string
     */
    public function getSERVER1() : string
    {
        return $this->SERVER_1;
    }

    /**
     * @return string
     */
    public function getGET1() : string
    {
        return $this->GET_1;
    }

    /**
     * @return string
     */
    public function getPOST1() : string
    {
        return $this->POST_1;
    }

    /**
     * @return string
     */
    public function getFILES1() : string
    {
        return $this->FILES_1;
    }

    /**
     * @return string
     */
    public function getREQUEST1() : string
    {
        return $this->REQUEST_1;
    }

    /**
     * @return string
     */
    public function getSESSION1() : string
    {
        return $this->SESSION_1;
    }

    /**
     * @return string
     */
    public function getENV1() : string
    {
        return $this->ENV_1;
    }

    /**
     * @return string
     */
    public function getCOOKIE1() : string
    {
        return $this->COOKIE_1;
    }

    /**
     * @return string
     */
    public function getPhpErrormsg1() : string
    {
        return $this->phpErrormsg_1;
    }

    /**
     * @return string
     */
    public function getHttpResponseHeader1() : string
    {
        return $this->httpResponseHeader_1;
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
        return $this->construct_1;
    }

    /**
     * @return string
     */
    public function getDestruct1() : string
    {
        return $this->destruct_1;
    }

    /**
     * @return string
     */
    public function getGet2() : string
    {
        return $this->get_2;
    }

    /**
     * @return string
     */
    public function getSet1() : string
    {
        return $this->set_1;
    }

    /**
     * @return string
     */
    public function getCall1() : string
    {
        return $this->call_1;
    }

    /**
     * @return string
     */
    public function getIsset1() : string
    {
        return $this->isset_1;
    }

    /**
     * @return string
     */
    public function getUnset1() : string
    {
        return $this->unset_1;
    }

    /**
     * @return string
     */
    public function getSleep1() : string
    {
        return $this->sleep_1;
    }

    /**
     * @return string
     */
    public function getWakeup1() : string
    {
        return $this->wakeup_1;
    }

    /**
     * @return string
     */
    public function getToString1() : string
    {
        return $this->toString_1;
    }

    /**
     * @return string
     */
    public function getInvoke1() : string
    {
        return $this->invoke_1;
    }

    /**
     * @return string
     */
    public function getDebugInfo1() : string
    {
        return $this->debugInfo_1;
    }

    /**
     * @return string
     */
    public function getClone2() : string
    {
        return $this->clone_2;
    }

    /**
     * @return string
     */
    public function getFiles() : string
    {
        return $this->files;
    }

    /**
     * @param string $GLOBALS_2
     * @return self
     * @param bool $validate
     */
    public function withGLOBALS2(string $GLOBALS_2, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($GLOBALS_2, self::$schema['properties']['_GLOBALS']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->GLOBALS_2 = $GLOBALS_2;

        return $clone;
    }

    /**
     * @param string $GLOBALS_3
     * @return self
     * @param bool $validate
     */
    public function withGLOBALS3(string $GLOBALS_3, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($GLOBALS_3, self::$schema['properties']['GLOBALS']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->GLOBALS_3 = $GLOBALS_3;

        return $clone;
    }

    /**
     * @param string $GLOBALS1_1
     * @return self
     * @param bool $validate
     */
    public function withGLOBALS11(string $GLOBALS1_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($GLOBALS1_1, self::$schema['properties']['GLOBALS_1']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->GLOBALS1_1 = $GLOBALS1_1;

        return $clone;
    }

    /**
     * @param string $SERVER_1
     * @return self
     * @param bool $validate
     */
    public function withSERVER1(string $SERVER_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($SERVER_1, self::$schema['properties']['_SERVER']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->SERVER_1 = $SERVER_1;

        return $clone;
    }

    /**
     * @param string $GET_1
     * @return self
     * @param bool $validate
     */
    public function withGET1(string $GET_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($GET_1, self::$schema['properties']['_GET']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->GET_1 = $GET_1;

        return $clone;
    }

    /**
     * @param string $POST_1
     * @return self
     * @param bool $validate
     */
    public function withPOST1(string $POST_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($POST_1, self::$schema['properties']['_POST']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->POST_1 = $POST_1;

        return $clone;
    }

    /**
     * @param string $FILES_1
     * @return self
     * @param bool $validate
     */
    public function withFILES1(string $FILES_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($FILES_1, self::$schema['properties']['_FILES']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->FILES_1 = $FILES_1;

        return $clone;
    }

    /**
     * @param string $REQUEST_1
     * @return self
     * @param bool $validate
     */
    public function withREQUEST1(string $REQUEST_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($REQUEST_1, self::$schema['properties']['_REQUEST']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->REQUEST_1 = $REQUEST_1;

        return $clone;
    }

    /**
     * @param string $SESSION_1
     * @return self
     * @param bool $validate
     */
    public function withSESSION1(string $SESSION_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($SESSION_1, self::$schema['properties']['_SESSION']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->SESSION_1 = $SESSION_1;

        return $clone;
    }

    /**
     * @param string $ENV_1
     * @return self
     * @param bool $validate
     */
    public function withENV1(string $ENV_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($ENV_1, self::$schema['properties']['_ENV']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->ENV_1 = $ENV_1;

        return $clone;
    }

    /**
     * @param string $COOKIE_1
     * @return self
     * @param bool $validate
     */
    public function withCOOKIE1(string $COOKIE_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($COOKIE_1, self::$schema['properties']['_COOKIE']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->COOKIE_1 = $COOKIE_1;

        return $clone;
    }

    /**
     * @param string $phpErrormsg_1
     * @return self
     * @param bool $validate
     */
    public function withPhpErrormsg1(string $phpErrormsg_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($phpErrormsg_1, self::$schema['properties']['php_errormsg']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->phpErrormsg_1 = $phpErrormsg_1;

        return $clone;
    }

    /**
     * @param string $httpResponseHeader_1
     * @return self
     * @param bool $validate
     */
    public function withHttpResponseHeader1(string $httpResponseHeader_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($httpResponseHeader_1, self::$schema['properties']['http_response_header']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->httpResponseHeader_1 = $httpResponseHeader_1;

        return $clone;
    }

    /**
     * @param string $argc_1
     * @return self
     * @param bool $validate
     */
    public function withArgc1(string $argc_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($argc_1, self::$schema['properties']['argc']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->argc_1 = $argc_1;

        return $clone;
    }

    /**
     * @param string $argv_1
     * @return self
     * @param bool $validate
     */
    public function withArgv1(string $argv_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($argv_1, self::$schema['properties']['argv']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->argv_1 = $argv_1;

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
     * @param string $buildFromInput_1
     * @return self
     * @param bool $validate
     */
    public function withBuildFromInput1(string $buildFromInput_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($buildFromInput_1, self::$schema['properties']['buildFromInput']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->buildFromInput_1 = $buildFromInput_1;

        return $clone;
    }

    /**
     * @param string $toArray_1
     * @return self
     * @param bool $validate
     */
    public function withToArray1(string $toArray_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($toArray_1, self::$schema['properties']['toArray']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->toArray_1 = $toArray_1;

        return $clone;
    }

    /**
     * @param string $validateInput_1
     * @return self
     * @param bool $validate
     */
    public function withValidateInput1(string $validateInput_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($validateInput_1, self::$schema['properties']['validateInput']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->validateInput_1 = $validateInput_1;

        return $clone;
    }

    /**
     * @param string $clone_1
     * @return self
     * @param bool $validate
     */
    public function withClone1(string $clone_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($clone_1, self::$schema['properties']['clone']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->clone_1 = $clone_1;

        return $clone;
    }

    /**
     * @param string $construct_1
     * @return self
     * @param bool $validate
     */
    public function withConstruct1(string $construct_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($construct_1, self::$schema['properties']['__construct']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->construct_1 = $construct_1;

        return $clone;
    }

    /**
     * @param string $destruct_1
     * @return self
     * @param bool $validate
     */
    public function withDestruct1(string $destruct_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($destruct_1, self::$schema['properties']['__destruct']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->destruct_1 = $destruct_1;

        return $clone;
    }

    /**
     * @param string $get_2
     * @return self
     * @param bool $validate
     */
    public function withGet2(string $get_2, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($get_2, self::$schema['properties']['__get']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->get_2 = $get_2;

        return $clone;
    }

    /**
     * @param string $set_1
     * @return self
     * @param bool $validate
     */
    public function withSet1(string $set_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($set_1, self::$schema['properties']['__set']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->set_1 = $set_1;

        return $clone;
    }

    /**
     * @param string $call_1
     * @return self
     * @param bool $validate
     */
    public function withCall1(string $call_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($call_1, self::$schema['properties']['__call']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->call_1 = $call_1;

        return $clone;
    }

    /**
     * @param string $isset_1
     * @return self
     * @param bool $validate
     */
    public function withIsset1(string $isset_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($isset_1, self::$schema['properties']['__isset']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->isset_1 = $isset_1;

        return $clone;
    }

    /**
     * @param string $unset_1
     * @return self
     * @param bool $validate
     */
    public function withUnset1(string $unset_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($unset_1, self::$schema['properties']['__unset']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->unset_1 = $unset_1;

        return $clone;
    }

    /**
     * @param string $sleep_1
     * @return self
     * @param bool $validate
     */
    public function withSleep1(string $sleep_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($sleep_1, self::$schema['properties']['__sleep']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->sleep_1 = $sleep_1;

        return $clone;
    }

    /**
     * @param string $wakeup_1
     * @return self
     * @param bool $validate
     */
    public function withWakeup1(string $wakeup_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($wakeup_1, self::$schema['properties']['__wakeup']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->wakeup_1 = $wakeup_1;

        return $clone;
    }

    /**
     * @param string $toString_1
     * @return self
     * @param bool $validate
     */
    public function withToString1(string $toString_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($toString_1, self::$schema['properties']['__toString']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->toString_1 = $toString_1;

        return $clone;
    }

    /**
     * @param string $invoke_1
     * @return self
     * @param bool $validate
     */
    public function withInvoke1(string $invoke_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($invoke_1, self::$schema['properties']['__invoke']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->invoke_1 = $invoke_1;

        return $clone;
    }

    /**
     * @param string $debugInfo_1
     * @return self
     * @param bool $validate
     */
    public function withDebugInfo1(string $debugInfo_1, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($debugInfo_1, self::$schema['properties']['__debugInfo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->debugInfo_1 = $debugInfo_1;

        return $clone;
    }

    /**
     * @param string $clone_2
     * @return self
     * @param bool $validate
     */
    public function withClone2(string $clone_2, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($clone_2, self::$schema['properties']['__clone']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->clone_2 = $clone_2;

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
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $_input, bool $_validate = true) : MyClass
    {
        $_input = is_array($_input) ? \JsonSchema\Validator::arrayToObjectRecursive($_input) : $_input;
        if ($_validate) {
            static::validateInput($_input);
        }

        $validate = $_validate;
        $GLOBALS_2 = $_input->{'_GLOBALS'};
        $GLOBALS_3 = $_input->{'GLOBALS'};
        $GLOBALS1_1 = $_input->{'GLOBALS_1'};
        $SERVER_1 = $_input->{'_SERVER'};
        $GET_1 = $_input->{'_GET'};
        $POST_1 = $_input->{'_POST'};
        $FILES_1 = $_input->{'_FILES'};
        $REQUEST_1 = $_input->{'_REQUEST'};
        $SESSION_1 = $_input->{'_SESSION'};
        $ENV_1 = $_input->{'_ENV'};
        $COOKIE_1 = $_input->{'_COOKIE'};
        $phpErrormsg_1 = $_input->{'php_errormsg'};
        $httpResponseHeader_1 = $_input->{'http_response_header'};
        $argc_1 = $_input->{'argc'};
        $argv_1 = $_input->{'argv'};
        $input = $_input->{'input'};
        $validate = $_input->{'validate'};
        $obj = $_input->{'obj'};
        $buildFromInput_1 = $_input->{'buildFromInput'};
        $toArray_1 = $_input->{'toArray'};
        $validateInput_1 = $_input->{'validateInput'};
        $clone_1 = $_input->{'clone'};
        $construct_1 = $_input->{'__construct'};
        $destruct_1 = $_input->{'__destruct'};
        $get_2 = $_input->{'__get'};
        $set_1 = $_input->{'__set'};
        $call_1 = $_input->{'__call'};
        $isset_1 = $_input->{'__isset'};
        $unset_1 = $_input->{'__unset'};
        $sleep_1 = $_input->{'__sleep'};
        $wakeup_1 = $_input->{'__wakeup'};
        $toString_1 = $_input->{'__toString'};
        $invoke_1 = $_input->{'__invoke'};
        $debugInfo_1 = $_input->{'__debugInfo'};
        $clone_2 = $_input->{'__clone'};
        $files = $_input->{'files'};

        $_obj = new self($GLOBALS_2, $GLOBALS_3, $GLOBALS1_1, $SERVER_1, $GET_1, $POST_1, $FILES_1, $REQUEST_1, $SESSION_1, $ENV_1, $COOKIE_1, $phpErrormsg_1, $httpResponseHeader_1, $argc_1, $argv_1, $input, $validate, $obj, $buildFromInput_1, $toArray_1, $validateInput_1, $clone_1, $construct_1, $destruct_1, $get_2, $set_1, $call_1, $isset_1, $unset_1, $sleep_1, $wakeup_1, $toString_1, $invoke_1, $debugInfo_1, $clone_2, $files);

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
        $output['_GLOBALS'] = $this->GLOBALS_2;
        $output['GLOBALS'] = $this->GLOBALS_3;
        $output['GLOBALS_1'] = $this->GLOBALS1_1;
        $output['_SERVER'] = $this->SERVER_1;
        $output['_GET'] = $this->GET_1;
        $output['_POST'] = $this->POST_1;
        $output['_FILES'] = $this->FILES_1;
        $output['_REQUEST'] = $this->REQUEST_1;
        $output['_SESSION'] = $this->SESSION_1;
        $output['_ENV'] = $this->ENV_1;
        $output['_COOKIE'] = $this->COOKIE_1;
        $output['php_errormsg'] = $this->phpErrormsg_1;
        $output['http_response_header'] = $this->httpResponseHeader_1;
        $output['argc'] = $this->argc_1;
        $output['argv'] = $this->argv_1;
        $output['input'] = $this->input;
        $output['validate'] = $this->validate;
        $output['obj'] = $this->obj;
        $output['buildFromInput'] = $this->buildFromInput_1;
        $output['toArray'] = $this->toArray_1;
        $output['validateInput'] = $this->validateInput_1;
        $output['clone'] = $this->clone_1;
        $output['__construct'] = $this->construct_1;
        $output['__destruct'] = $this->destruct_1;
        $output['__get'] = $this->get_2;
        $output['__set'] = $this->set_1;
        $output['__call'] = $this->call_1;
        $output['__isset'] = $this->isset_1;
        $output['__unset'] = $this->unset_1;
        $output['__sleep'] = $this->sleep_1;
        $output['__wakeup'] = $this->wakeup_1;
        $output['__toString'] = $this->toString_1;
        $output['__invoke'] = $this->invoke_1;
        $output['__debugInfo'] = $this->debugInfo_1;
        $output['__clone'] = $this->clone_2;
        $output['files'] = $this->files;

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
