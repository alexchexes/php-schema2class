<?php

namespace Ns\FallbackNaming_5_6;

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
            '__clone',
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
            '__clone' => [
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
    private $_GLOBALS;

    /**
     * @var string
     */
    private $GLOBALS;

    /**
     * @var string
     */
    private $GLOBALS1;

    /**
     * @var string
     */
    private $SERVER;

    /**
     * @var string
     */
    private $GET;

    /**
     * @var string
     */
    private $POST;

    /**
     * @var string
     */
    private $FILES;

    /**
     * @var string
     */
    private $REQUEST;

    /**
     * @var string
     */
    private $SESSION;

    /**
     * @var string
     */
    private $ENV;

    /**
     * @var string
     */
    private $COOKIE;

    /**
     * @var string
     */
    private $phpErrormsg;

    /**
     * @var string
     */
    private $httpResponseHeader;

    /**
     * @var string
     */
    private $argc;

    /**
     * @var string
     */
    private $argv;

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
    private $fromInput;

    /**
     * @var string
     */
    private $toArray;

    /**
     * @var string
     */
    private $toStdClass;

    /**
     * @var string
     */
    private $validateInput;

    /**
     * @var string
     */
    private $_schema_1;

    /**
     * @var string
     */
    private $schema;

    /**
     * @var string
     */
    private $_defaults_1;

    /**
     * @var string
     */
    private $defaults;

    /**
     * @var string
     */
    private $providedOptionals;

    /**
     * @var string|null
     */
    private $_providedOptionals_1 = null;

    /**
     * @var string
     */
    private $clone;

    /**
     * @var string
     */
    private $_clone;

    /**
     * @var string
     */
    private $construct;

    /**
     * @var string
     */
    private $destruct;

    /**
     * @var string
     */
    private $get;

    /**
     * @var string
     */
    private $set;

    /**
     * @var string
     */
    private $call;

    /**
     * @var string
     */
    private $isset;

    /**
     * @var string
     */
    private $unset;

    /**
     * @var string
     */
    private $sleep;

    /**
     * @var string
     */
    private $wakeup;

    /**
     * @var string
     */
    private $toString;

    /**
     * @var string
     */
    private $invoke;

    /**
     * @var string
     */
    private $debugInfo;

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
     * @param string $_GLOBALS
     * @param string $GLOBALS1
     * @param string $SERVER
     * @param string $GET
     * @param string $POST
     * @param string $FILES
     * @param string $REQUEST
     * @param string $SESSION
     * @param string $ENV
     * @param string $COOKIE
     * @param string $phpErrormsg
     * @param string $httpResponseHeader
     * @param string $_argc
     * @param string $_argv
     * @param string $_input
     * @param string $_obj
     * @param string $_includeDefaults
     * @param string $fromInput
     * @param string $toArray
     * @param string $toStdClass
     * @param string $validateInput
     * @param string $_schema_1
     * @param string $schema
     * @param string $_defaults_1
     * @param string $defaults
     * @param string $providedOptionals
     * @param string $_clone
     * @param string $_clone_1
     * @param string $construct
     * @param string $destruct
     * @param string $get
     * @param string $set
     * @param string $call
     * @param string $isset
     * @param string $unset
     * @param string $sleep
     * @param string $wakeup
     * @param string $toString
     * @param string $invoke
     * @param string $debugInfo
     * @param string $files
     * @param string $_this
     */
    public function __construct($_GLOBALS_1, $_GLOBALS, $GLOBALS1, $SERVER, $GET, $POST, $FILES, $REQUEST, $SESSION, $ENV, $COOKIE, $phpErrormsg, $httpResponseHeader, $_argc, $_argv, $_input, $_obj, $_includeDefaults, $fromInput, $toArray, $toStdClass, $validateInput, $_schema_1, $schema, $_defaults_1, $defaults, $providedOptionals, $_clone, $_clone_1, $construct, $destruct, $get, $set, $call, $isset, $unset, $sleep, $wakeup, $toString, $invoke, $debugInfo, $files, $_this)
    {
        $this->_GLOBALS = $_GLOBALS_1;
        $this->GLOBALS = $_GLOBALS;
        $this->GLOBALS1 = $GLOBALS1;
        $this->SERVER = $SERVER;
        $this->GET = $GET;
        $this->POST = $POST;
        $this->FILES = $FILES;
        $this->REQUEST = $REQUEST;
        $this->SESSION = $SESSION;
        $this->ENV = $ENV;
        $this->COOKIE = $COOKIE;
        $this->phpErrormsg = $phpErrormsg;
        $this->httpResponseHeader = $httpResponseHeader;
        $this->argc = $_argc;
        $this->argv = $_argv;
        $this->input = $_input;
        $this->obj = $_obj;
        $this->includeDefaults = $_includeDefaults;
        $this->fromInput = $fromInput;
        $this->toArray = $toArray;
        $this->toStdClass = $toStdClass;
        $this->validateInput = $validateInput;
        $this->_schema_1 = $_schema_1;
        $this->schema = $schema;
        $this->_defaults_1 = $_defaults_1;
        $this->defaults = $defaults;
        $this->providedOptionals = $providedOptionals;
        $this->clone = $_clone;
        $this->_clone = $_clone_1;
        $this->construct = $construct;
        $this->destruct = $destruct;
        $this->get = $get;
        $this->set = $set;
        $this->call = $call;
        $this->isset = $isset;
        $this->unset = $unset;
        $this->sleep = $sleep;
        $this->wakeup = $wakeup;
        $this->toString = $toString;
        $this->invoke = $invoke;
        $this->debugInfo = $debugInfo;
        $this->files = $files;
        $this->_this = $_this;
    }

    /**
     * @return string
     */
    public function get_GLOBALS()
    {
        return $this->_GLOBALS;
    }

    /**
     * @param string $_GLOBALS_1
     * @return self
     * @param bool $validate
     */
    public function with_GLOBALS($_GLOBALS_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_GLOBALS_1, self::$_schema['properties']['_GLOBALS']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_GLOBALS = $_GLOBALS_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getGLOBALS()
    {
        return $this->GLOBALS;
    }

    /**
     * @param string $_GLOBALS
     * @return self
     * @param bool $validate
     */
    public function withGLOBALS($_GLOBALS, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_GLOBALS, self::$_schema['properties']['GLOBALS']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->GLOBALS = $_GLOBALS;

        return $clone;
    }

    /**
     * @return string
     */
    public function getGLOBALS1()
    {
        return $this->GLOBALS1;
    }

    /**
     * @param string $GLOBALS1
     * @return self
     * @param bool $validate
     */
    public function withGLOBALS1($GLOBALS1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($GLOBALS1, self::$_schema['properties']['GLOBALS_1']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->GLOBALS1 = $GLOBALS1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getSERVER()
    {
        return $this->SERVER;
    }

    /**
     * @param string $SERVER
     * @return self
     * @param bool $validate
     */
    public function withSERVER($SERVER, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($SERVER, self::$_schema['properties']['_SERVER']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->SERVER = $SERVER;

        return $clone;
    }

    /**
     * @return string
     */
    public function getGET()
    {
        return $this->GET;
    }

    /**
     * @param string $GET
     * @return self
     * @param bool $validate
     */
    public function withGET($GET, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($GET, self::$_schema['properties']['_GET']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->GET = $GET;

        return $clone;
    }

    /**
     * @return string
     */
    public function getPOST()
    {
        return $this->POST;
    }

    /**
     * @param string $POST
     * @return self
     * @param bool $validate
     */
    public function withPOST($POST, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($POST, self::$_schema['properties']['_POST']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->POST = $POST;

        return $clone;
    }

    /**
     * @return string
     */
    public function getFILES()
    {
        return $this->FILES;
    }

    /**
     * @param string $FILES
     * @return self
     * @param bool $validate
     */
    public function withFILES($FILES, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($FILES, self::$_schema['properties']['_FILES']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->FILES = $FILES;

        return $clone;
    }

    /**
     * @return string
     */
    public function getREQUEST()
    {
        return $this->REQUEST;
    }

    /**
     * @param string $REQUEST
     * @return self
     * @param bool $validate
     */
    public function withREQUEST($REQUEST, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($REQUEST, self::$_schema['properties']['_REQUEST']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->REQUEST = $REQUEST;

        return $clone;
    }

    /**
     * @return string
     */
    public function getSESSION()
    {
        return $this->SESSION;
    }

    /**
     * @param string $SESSION
     * @return self
     * @param bool $validate
     */
    public function withSESSION($SESSION, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($SESSION, self::$_schema['properties']['_SESSION']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->SESSION = $SESSION;

        return $clone;
    }

    /**
     * @return string
     */
    public function getENV()
    {
        return $this->ENV;
    }

    /**
     * @param string $ENV
     * @return self
     * @param bool $validate
     */
    public function withENV($ENV, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($ENV, self::$_schema['properties']['_ENV']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->ENV = $ENV;

        return $clone;
    }

    /**
     * @return string
     */
    public function getCOOKIE()
    {
        return $this->COOKIE;
    }

    /**
     * @param string $COOKIE
     * @return self
     * @param bool $validate
     */
    public function withCOOKIE($COOKIE, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($COOKIE, self::$_schema['properties']['_COOKIE']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->COOKIE = $COOKIE;

        return $clone;
    }

    /**
     * @return string
     */
    public function getPhpErrormsg()
    {
        return $this->phpErrormsg;
    }

    /**
     * @param string $phpErrormsg
     * @return self
     * @param bool $validate
     */
    public function withPhpErrormsg($phpErrormsg, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($phpErrormsg, self::$_schema['properties']['php_errormsg']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->phpErrormsg = $phpErrormsg;

        return $clone;
    }

    /**
     * @return string
     */
    public function getHttpResponseHeader()
    {
        return $this->httpResponseHeader;
    }

    /**
     * @param string $httpResponseHeader
     * @return self
     * @param bool $validate
     */
    public function withHttpResponseHeader($httpResponseHeader, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($httpResponseHeader, self::$_schema['properties']['http_response_header']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->httpResponseHeader = $httpResponseHeader;

        return $clone;
    }

    /**
     * @return string
     */
    public function getArgc()
    {
        return $this->argc;
    }

    /**
     * @param string $_argc
     * @return self
     * @param bool $validate
     */
    public function withArgc($_argc, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_argc, self::$_schema['properties']['argc']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->argc = $_argc;

        return $clone;
    }

    /**
     * @return string
     */
    public function getArgv()
    {
        return $this->argv;
    }

    /**
     * @param string $_argv
     * @return self
     * @param bool $validate
     */
    public function withArgv($_argv, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_argv, self::$_schema['properties']['argv']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->argv = $_argv;

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
     * @param string $_input
     * @return self
     * @param bool $validate
     */
    public function withInput($_input, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_input, self::$_schema['properties']['input']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->input = $_input;

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
     * @param string $_validate
     * @return self
     * @param bool $validate
     */
    public function withValidate($_validate, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_validate, self::$_schema['properties']['validate']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->validate = $_validate;

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
     * @param string|null $_materializeDefaults
     * @return self
     * @param bool $validate
     */
    public function withMaterializeDefaults($_materializeDefaults, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_materializeDefaults, self::$_schema['properties']['materializeDefaults']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->materializeDefaults = $_materializeDefaults;
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
     * @param string $_obj
     * @return self
     * @param bool $validate
     */
    public function withObj($_obj, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_obj, self::$_schema['properties']['obj']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->obj = $_obj;

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
     * @param string $_includeDefaults
     * @return self
     * @param bool $validate
     */
    public function withIncludeDefaults($_includeDefaults, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_includeDefaults, self::$_schema['properties']['includeDefaults']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->includeDefaults = $_includeDefaults;

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
     * @param bool $validate
     */
    public function withTestObj(MyClassTestObj $testObj, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($testObj, self::$_schema['properties']['testObj']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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
    public function getFromInput()
    {
        return $this->fromInput;
    }

    /**
     * @param string $fromInput
     * @return self
     * @param bool $validate
     */
    public function withFromInput($fromInput, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($fromInput, self::$_schema['properties']['fromInput']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->fromInput = $fromInput;

        return $clone;
    }

    /**
     * @return string
     */
    public function getToArray()
    {
        return $this->toArray;
    }

    /**
     * @param string $toArray
     * @return self
     * @param bool $validate
     */
    public function withToArray($toArray, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($toArray, self::$_schema['properties']['toArray']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->toArray = $toArray;

        return $clone;
    }

    /**
     * @return string
     */
    public function getToStdClass()
    {
        return $this->toStdClass;
    }

    /**
     * @param string $toStdClass
     * @return self
     * @param bool $validate
     */
    public function withToStdClass($toStdClass, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($toStdClass, self::$_schema['properties']['toStdClass']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->toStdClass = $toStdClass;

        return $clone;
    }

    /**
     * @return string
     */
    public function getValidateInput()
    {
        return $this->validateInput;
    }

    /**
     * @param string $validateInput
     * @return self
     * @param bool $validate
     */
    public function withValidateInput($validateInput, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($validateInput, self::$_schema['properties']['validateInput']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->validateInput = $validateInput;

        return $clone;
    }

    /**
     * @return string
     */
    public function getSchema1()
    {
        return $this->_schema_1;
    }

    /**
     * @param string $_schema_1
     * @return self
     * @param bool $validate
     */
    public function withSchema1($_schema_1, bool $validate = true)
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
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param string $schema
     * @return self
     * @param bool $validate
     */
    public function withSchema($schema, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($schema, self::$_schema['properties']['schema']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->schema = $schema;

        return $clone;
    }

    /**
     * @return string
     */
    public function getDefaults1()
    {
        return $this->_defaults_1;
    }

    /**
     * @param string $_defaults_1
     * @return self
     * @param bool $validate
     */
    public function withDefaults1($_defaults_1, bool $validate = true)
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
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @param string $defaults
     * @return self
     * @param bool $validate
     */
    public function withDefaults($defaults, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($defaults, self::$_schema['properties']['defaults']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->defaults = $defaults;

        return $clone;
    }

    /**
     * @return string
     */
    public function getProvidedOptionals()
    {
        return $this->providedOptionals;
    }

    /**
     * @param string $providedOptionals
     * @return self
     * @param bool $validate
     */
    public function withProvidedOptionals($providedOptionals, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($providedOptionals, self::$_schema['properties']['_providedOptionals']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->providedOptionals = $providedOptionals;

        return $clone;
    }

    /**
     * @return string|null
     */
    public function getProvidedOptionals1()
    {
        return $this->_providedOptionals_1;
    }

    /**
     * @param string $_providedOptionals_1
     * @return self
     * @param bool $validate
     */
    public function withProvidedOptionals1($_providedOptionals_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_providedOptionals_1, self::$_schema['properties']['__providedOptionals']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_providedOptionals_1 = $_providedOptionals_1;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutProvidedOptionals1()
    {
        $clone = clone $this;
        unset($clone->_providedOptionals_1);

        return $clone;
    }

    /**
     * @return string
     */
    public function getClone()
    {
        return $this->clone;
    }

    /**
     * @param string $_clone
     * @return self
     * @param bool $validate
     */
    public function withClone($_clone, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_clone, self::$_schema['properties']['clone']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->clone = $_clone;

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
     * @param string $_clone_1
     * @return self
     * @param bool $validate
     */
    public function with_Clone($_clone_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_clone_1, self::$_schema['properties']['__clone']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_clone = $_clone_1;

        return $clone;
    }

    /**
     * @return string
     */
    public function getConstruct()
    {
        return $this->construct;
    }

    /**
     * @param string $construct
     * @return self
     * @param bool $validate
     */
    public function withConstruct($construct, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($construct, self::$_schema['properties']['__construct']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->construct = $construct;

        return $clone;
    }

    /**
     * @return string
     */
    public function getDestruct()
    {
        return $this->destruct;
    }

    /**
     * @param string $destruct
     * @return self
     * @param bool $validate
     */
    public function withDestruct($destruct, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($destruct, self::$_schema['properties']['__destruct']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->destruct = $destruct;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_Get()
    {
        return $this->get;
    }

    /**
     * @param string $get
     * @return self
     * @param bool $validate
     */
    public function with_Get($get, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($get, self::$_schema['properties']['__get']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->get = $get;

        return $clone;
    }

    /**
     * @return string
     */
    public function getSet()
    {
        return $this->set;
    }

    /**
     * @param string $set
     * @return self
     * @param bool $validate
     */
    public function withSet($set, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($set, self::$_schema['properties']['__set']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->set = $set;

        return $clone;
    }

    /**
     * @return string
     */
    public function getCall()
    {
        return $this->call;
    }

    /**
     * @param string $call
     * @return self
     * @param bool $validate
     */
    public function withCall($call, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($call, self::$_schema['properties']['__call']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->call = $call;

        return $clone;
    }

    /**
     * @return string
     */
    public function getIsset()
    {
        return $this->isset;
    }

    /**
     * @param string $isset
     * @return self
     * @param bool $validate
     */
    public function withIsset($isset, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($isset, self::$_schema['properties']['__isset']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->isset = $isset;

        return $clone;
    }

    /**
     * @return string
     */
    public function getUnset()
    {
        return $this->unset;
    }

    /**
     * @param string $unset
     * @return self
     * @param bool $validate
     */
    public function withUnset($unset, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($unset, self::$_schema['properties']['__unset']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->unset = $unset;

        return $clone;
    }

    /**
     * @return string
     */
    public function getSleep()
    {
        return $this->sleep;
    }

    /**
     * @param string $sleep
     * @return self
     * @param bool $validate
     */
    public function withSleep($sleep, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($sleep, self::$_schema['properties']['__sleep']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->sleep = $sleep;

        return $clone;
    }

    /**
     * @return string
     */
    public function getWakeup()
    {
        return $this->wakeup;
    }

    /**
     * @param string $wakeup
     * @return self
     * @param bool $validate
     */
    public function withWakeup($wakeup, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($wakeup, self::$_schema['properties']['__wakeup']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->wakeup = $wakeup;

        return $clone;
    }

    /**
     * @return string
     */
    public function getToString()
    {
        return $this->toString;
    }

    /**
     * @param string $toString
     * @return self
     * @param bool $validate
     */
    public function withToString($toString, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($toString, self::$_schema['properties']['__toString']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->toString = $toString;

        return $clone;
    }

    /**
     * @return string
     */
    public function getInvoke()
    {
        return $this->invoke;
    }

    /**
     * @param string $invoke
     * @return self
     * @param bool $validate
     */
    public function withInvoke($invoke, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($invoke, self::$_schema['properties']['__invoke']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->invoke = $invoke;

        return $clone;
    }

    /**
     * @return string
     */
    public function getDebugInfo()
    {
        return $this->debugInfo;
    }

    /**
     * @param string $debugInfo
     * @return self
     * @param bool $validate
     */
    public function withDebugInfo($debugInfo, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($debugInfo, self::$_schema['properties']['__debugInfo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->debugInfo = $debugInfo;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_Files()
    {
        return $this->files;
    }

    /**
     * @param string $files
     * @return self
     * @param bool $validate
     */
    public function with_Files($files, bool $validate = true)
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
    public function getThis()
    {
        return $this->_this;
    }

    /**
     * @param string $_this
     * @return self
     * @param bool $validate
     */
    public function withThis($_this, bool $validate = true)
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
     * @param bool $validate
     */
    public function withEnsureArgs1($ensureArgs1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($ensureArgs1, self::$_schema['properties']['ensureArgs1']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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
     * @param bool $validate
     */
    public function withEnsureArgs2(MyClassEnsureArgs2 $ensureArgs2, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($ensureArgs2, self::$_schema['properties']['ensureArgs2']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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

        $__providedOptionals = [];
        $_GLOBALS_1 = $input->{'_GLOBALS'};
        $_GLOBALS = $input->{'GLOBALS'};
        $GLOBALS1 = $input->{'GLOBALS_1'};
        $SERVER = $input->{'_SERVER'};
        $GET = $input->{'_GET'};
        $POST = $input->{'_POST'};
        $FILES = $input->{'_FILES'};
        $REQUEST = $input->{'_REQUEST'};
        $SESSION = $input->{'_SESSION'};
        $ENV = $input->{'_ENV'};
        $COOKIE = $input->{'_COOKIE'};
        $phpErrormsg = $input->{'php_errormsg'};
        $httpResponseHeader = $input->{'http_response_header'};
        $_argc = $input->{'argc'};
        $_argv = $input->{'argv'};
        $_input = $input->{'input'};
        $_validate = isset($input->{'validate'}) ? $input->{'validate'} : null;
        $_materializeDefaults = property_exists($input, 'materializeDefaults') ? ($input->{'materializeDefaults'} !== null ? $input->{'materializeDefaults'} : null) : null;
        if (property_exists($input, 'materializeDefaults')) {
            $__providedOptionals['materializeDefaults'] = true;
        }
        $_obj = $input->{'obj'};
        $_includeDefaults = $input->{'includeDefaults'};
        $testObj = isset($input->{'testObj'}) ? MyClassTestObj::fromInput($input->{'testObj'}, $validate, $materializeDefaults) : null;
        $fromInput = $input->{'fromInput'};
        $toArray = $input->{'toArray'};
        $toStdClass = $input->{'toStdClass'};
        $validateInput = $input->{'validateInput'};
        $_schema_1 = $input->{'_schema'};
        $schema = $input->{'schema'};
        $_defaults_1 = $input->{'_defaults'};
        $defaults = $input->{'defaults'};
        $providedOptionals = $input->{'_providedOptionals'};
        $_providedOptionals_1 = isset($input->{'__providedOptionals'}) ? $input->{'__providedOptionals'} : null;
        $_clone = $input->{'clone'};
        $_clone_1 = $input->{'__clone'};
        $construct = $input->{'__construct'};
        $destruct = $input->{'__destruct'};
        $get = $input->{'__get'};
        $set = $input->{'__set'};
        $call = $input->{'__call'};
        $isset = $input->{'__isset'};
        $unset = $input->{'__unset'};
        $sleep = $input->{'__sleep'};
        $wakeup = $input->{'__wakeup'};
        $toString = $input->{'__toString'};
        $invoke = $input->{'__invoke'};
        $debugInfo = $input->{'__debugInfo'};
        $files = $input->{'files'};
        $_this = $input->{'this'};
        $ensureArgs1 = isset($input->{'ensureArgs1'}) ? ((is_string($input->{'ensureArgs1'})) ? $input->{'ensureArgs1'} : (((MyClassEnsureArgs1Alternative2::validateInput($input->{'ensureArgs1'}, true)) ? MyClassEnsureArgs1Alternative2::fromInput($input->{'ensureArgs1'}, $validate, $materializeDefaults) : (((MyClassEnsureArgs1Alternative1::validateInput($input->{'ensureArgs1'}, true)) ? MyClassEnsureArgs1Alternative1::fromInput($input->{'ensureArgs1'}, $validate, $materializeDefaults) : (null)))))) : null;
        $ensureArgs2 = isset($input->{'ensureArgs2'}) ? MyClassEnsureArgs2::fromInput($input->{'ensureArgs2'}, $validate, $materializeDefaults) : null;
        $ensureArgs3 = isset($input->{'ensureArgs3'}) ? array_map(function($i) use ($validate, $materializeDefaults) { return MyClassEnsureArgs3Item::fromInput($i, $validate, $materializeDefaults); }, $input->{'ensureArgs3'}) : null;

        $obj = new self($_GLOBALS_1, $_GLOBALS, $GLOBALS1, $SERVER, $GET, $POST, $FILES, $REQUEST, $SESSION, $ENV, $COOKIE, $phpErrormsg, $httpResponseHeader, $_argc, $_argv, $_input, $_obj, $_includeDefaults, $fromInput, $toArray, $toStdClass, $validateInput, $_schema_1, $schema, $_defaults_1, $defaults, $providedOptionals, $_clone, $_clone_1, $construct, $destruct, $get, $set, $call, $isset, $unset, $sleep, $wakeup, $toString, $invoke, $debugInfo, $files, $_this);
        $obj->validate = $_validate;
        $obj->materializeDefaults = $_materializeDefaults;
        $obj->testObj = $testObj;
        $obj->_providedOptionals_1 = $_providedOptionals_1;
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
    public function toArray(bool $includeDefaults = false)
    {
        $output = [];
        $output['_GLOBALS'] = $this->_GLOBALS;
        $output['GLOBALS'] = $this->GLOBALS;
        $output['GLOBALS_1'] = $this->GLOBALS1;
        $output['_SERVER'] = $this->SERVER;
        $output['_GET'] = $this->GET;
        $output['_POST'] = $this->POST;
        $output['_FILES'] = $this->FILES;
        $output['_REQUEST'] = $this->REQUEST;
        $output['_SESSION'] = $this->SESSION;
        $output['_ENV'] = $this->ENV;
        $output['_COOKIE'] = $this->COOKIE;
        $output['php_errormsg'] = $this->phpErrormsg;
        $output['http_response_header'] = $this->httpResponseHeader;
        $output['argc'] = $this->argc;
        $output['argv'] = $this->argv;
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
        $output['fromInput'] = $this->fromInput;
        $output['toArray'] = $this->toArray;
        $output['toStdClass'] = $this->toStdClass;
        $output['validateInput'] = $this->validateInput;
        $output['_schema'] = $this->_schema_1;
        $output['schema'] = $this->schema;
        $output['_defaults'] = $this->_defaults_1;
        $output['defaults'] = $this->defaults;
        $output['_providedOptionals'] = $this->providedOptionals;
        if (isset($this->_providedOptionals_1)) {
            $output['__providedOptionals'] = $this->_providedOptionals_1;
        }
        $output['clone'] = $this->clone;
        $output['__clone'] = $this->_clone;
        $output['__construct'] = $this->construct;
        $output['__destruct'] = $this->destruct;
        $output['__get'] = $this->get;
        $output['__set'] = $this->set;
        $output['__call'] = $this->call;
        $output['__isset'] = $this->isset;
        $output['__unset'] = $this->unset;
        $output['__sleep'] = $this->sleep;
        $output['__wakeup'] = $this->wakeup;
        $output['__toString'] = $this->toString;
        $output['__invoke'] = $this->invoke;
        $output['__debugInfo'] = $this->debugInfo;
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
        $output->{'_GLOBALS'} = $this->_GLOBALS;
        $output->{'GLOBALS'} = $this->GLOBALS;
        $output->{'GLOBALS_1'} = $this->GLOBALS1;
        $output->{'_SERVER'} = $this->SERVER;
        $output->{'_GET'} = $this->GET;
        $output->{'_POST'} = $this->POST;
        $output->{'_FILES'} = $this->FILES;
        $output->{'_REQUEST'} = $this->REQUEST;
        $output->{'_SESSION'} = $this->SESSION;
        $output->{'_ENV'} = $this->ENV;
        $output->{'_COOKIE'} = $this->COOKIE;
        $output->{'php_errormsg'} = $this->phpErrormsg;
        $output->{'http_response_header'} = $this->httpResponseHeader;
        $output->{'argc'} = $this->argc;
        $output->{'argv'} = $this->argv;
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
        $output->{'fromInput'} = $this->fromInput;
        $output->{'toArray'} = $this->toArray;
        $output->{'toStdClass'} = $this->toStdClass;
        $output->{'validateInput'} = $this->validateInput;
        $output->{'_schema'} = $this->_schema_1;
        $output->{'schema'} = $this->schema;
        $output->{'_defaults'} = $this->_defaults_1;
        $output->{'defaults'} = $this->defaults;
        $output->{'_providedOptionals'} = $this->providedOptionals;
        if (isset($this->_providedOptionals_1)) {
            $output->{'__providedOptionals'} = $this->_providedOptionals_1;
        }
        $output->{'clone'} = $this->clone;
        $output->{'__clone'} = $this->_clone;
        $output->{'__construct'} = $this->construct;
        $output->{'__destruct'} = $this->destruct;
        $output->{'__get'} = $this->get;
        $output->{'__set'} = $this->set;
        $output->{'__call'} = $this->call;
        $output->{'__isset'} = $this->isset;
        $output->{'__unset'} = $this->unset;
        $output->{'__sleep'} = $this->sleep;
        $output->{'__wakeup'} = $this->wakeup;
        $output->{'__toString'} = $this->toString;
        $output->{'__invoke'} = $this->invoke;
        $output->{'__debugInfo'} = $this->debugInfo;
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
