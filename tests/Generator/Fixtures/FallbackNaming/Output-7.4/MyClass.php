<?php

declare(strict_types=1);

namespace Ns\FallbackNaming_7_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
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
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        '_GLOBALS' => '_GLOBALS',
        'GLOBALS' => 'GLOBALS',
        'GLOBALS_1' => 'GLOBALS1',
        '_SERVER' => 'SERVER',
        '_GET' => 'GET',
        '_POST' => 'POST',
        '_FILES' => 'FILES',
        '_REQUEST' => 'REQUEST',
        '_SESSION' => 'SESSION',
        '_ENV' => 'ENV',
        '_COOKIE' => 'COOKIE',
        'php_errormsg' => 'phpErrormsg',
        'http_response_header' => 'httpResponseHeader',
        'argc' => 'argc',
        'argv' => 'argv',
        'input' => 'input',
        'validate' => 'validate',
        'materializeDefaults' => 'materializeDefaults',
        'obj' => 'obj',
        'includeDefaults' => 'includeDefaults',
        'testObj' => 'testObj',
        'fromInput' => 'fromInput',
        'toArray' => 'toArray',
        'toStdClass' => 'toStdClass',
        'validateInput' => 'validateInput',
        '_schema' => '_schema_1',
        'schema' => 'schema',
        '_defaults' => '_defaults_1',
        'defaults' => 'defaults',
        '_providedOptionals' => 'providedOptionals',
        '__providedOptionals' => '_providedOptionals_1',
        'clone' => 'clone',
        '__clone' => '_clone',
        '__construct' => 'construct',
        '__destruct' => 'destruct',
        '__get' => 'get',
        '__set' => 'set',
        '__call' => 'call',
        '__isset' => 'isset',
        '__unset' => 'unset',
        '__sleep' => 'sleep',
        '__wakeup' => 'wakeup',
        '__toString' => 'toString',
        '__invoke' => 'invoke',
        '__debugInfo' => 'debugInfo',
        'files' => 'files',
        'this' => '_this',
        'ensureArgs1' => 'ensureArgs1',
        'ensureArgs2' => 'ensureArgs2',
        'ensureArgs3' => 'ensureArgs3',
    ];

    /**
     * Default values from the schema
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
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private string $_GLOBALS;

    private string $GLOBALS;

    private string $GLOBALS1;

    private string $SERVER;

    private string $GET;

    private string $POST;

    private string $FILES;

    private string $REQUEST;

    private string $SESSION;

    private string $ENV;

    private string $COOKIE;

    private string $phpErrormsg;

    private string $httpResponseHeader;

    private string $argc;

    private string $argv;

    private string $input;

    private ?string $validate = null;

    private ?string $materializeDefaults = null;

    private string $obj;

    private string $includeDefaults;

    private ?MyClassTestObj $testObj = null;

    private string $fromInput;

    private string $toArray;

    private string $toStdClass;

    private string $validateInput;

    private string $_schema_1;

    private string $schema;

    private string $_defaults_1;

    private string $defaults;

    private string $providedOptionals;

    private ?string $_providedOptionals_1 = null;

    private string $clone;

    private string $_clone;

    private string $construct;

    private string $destruct;

    private string $get;

    private string $set;

    private string $call;

    private string $isset;

    private string $unset;

    private string $sleep;

    private string $wakeup;

    private string $toString;

    private string $invoke;

    private string $debugInfo;

    private string $files;

    private string $_this;

    /**
     * @var MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string|null
     */
    private $ensureArgs1 = null;

    private ?MyClassEnsureArgs2 $ensureArgs2 = null;

    /**
     * @var MyClassEnsureArgs3Item[]|null
     */
    private ?array $ensureArgs3 = null;

    /**
     * @param MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string|null $ensureArgs1
     * @param MyClassEnsureArgs3Item[]|null $ensureArgs3
     */
    public function __construct(
        string $_GLOBALS_1,
        string $_GLOBALS,
        string $GLOBALS1,
        string $SERVER,
        string $GET,
        string $POST,
        string $FILES,
        string $REQUEST,
        string $SESSION,
        string $ENV,
        string $COOKIE,
        string $phpErrormsg,
        string $httpResponseHeader,
        string $_argc,
        string $_argv,
        string $_input,
        string $_obj,
        string $_includeDefaults,
        string $fromInput,
        string $toArray,
        string $toStdClass,
        string $validateInput,
        string $_schema_1,
        string $schema,
        string $_defaults_1,
        string $defaults,
        string $providedOptionals,
        string $_clone,
        string $_clone_1,
        string $construct,
        string $destruct,
        string $get,
        string $set,
        string $call,
        string $isset,
        string $unset,
        string $sleep,
        string $wakeup,
        string $toString,
        string $invoke,
        string $debugInfo,
        string $files,
        string $_this,
        ?string $_validate = null,
        ?string $_materializeDefaults = null,
        ?MyClassTestObj $testObj = null,
        ?string $_providedOptionals_1 = null,
        $ensureArgs1 = null,
        ?MyClassEnsureArgs2 $ensureArgs2 = null,
        ?array $ensureArgs3 = null
    ) {
        $this->_additionalProperties = new \stdClass();

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
        $this->validate = $_validate;
        if ($_materializeDefaults !== null) {
            $this->materializeDefaults = $_materializeDefaults;
            $this->_providedOptionals['materializeDefaults'] = true;
        };
        $this->testObj = $testObj;
        $this->_providedOptionals_1 = $_providedOptionals_1;
        $this->ensureArgs1 = $ensureArgs1;
        $this->ensureArgs2 = $ensureArgs2;
        $this->ensureArgs3 = $ensureArgs3;
    }

    /**
     * Object (`stdClass`) or array with name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return an associative array instead of `stdClass` object.
     * @return array|\stdClass
     */
    public function getAdditionalProperties(bool $asArray = true)
    {
        return $asArray
            ? json_decode(json_encode($this->_additionalProperties), true)
            : $this->_additionalProperties;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param \stdClass|array $additionalProperties Map of property name/value pairs to add.
     */
    public function withAdditionalProperties($additionalProperties): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $clone;
    }

    /**
     * Removes all extra properties not specified in the schema.
     */
    public function withoutAdditionalProperties(): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = new \stdClass();
        return $clone;
    }

    public function get_GLOBALS(): string
    {
        return $this->_GLOBALS;
    }

    public function with_GLOBALS(string $_GLOBALS_1): self
    {
        $clone = clone $this;
        $clone->_GLOBALS = $_GLOBALS_1;

        return $clone;
    }

    public function getGLOBALS(): string
    {
        return $this->GLOBALS;
    }

    public function withGLOBALS(string $_GLOBALS): self
    {
        $clone = clone $this;
        $clone->GLOBALS = $_GLOBALS;

        return $clone;
    }

    public function getGLOBALS1(): string
    {
        return $this->GLOBALS1;
    }

    public function withGLOBALS1(string $GLOBALS1): self
    {
        $clone = clone $this;
        $clone->GLOBALS1 = $GLOBALS1;

        return $clone;
    }

    public function getSERVER(): string
    {
        return $this->SERVER;
    }

    public function withSERVER(string $SERVER): self
    {
        $clone = clone $this;
        $clone->SERVER = $SERVER;

        return $clone;
    }

    public function getGET(): string
    {
        return $this->GET;
    }

    public function withGET(string $GET): self
    {
        $clone = clone $this;
        $clone->GET = $GET;

        return $clone;
    }

    public function getPOST(): string
    {
        return $this->POST;
    }

    public function withPOST(string $POST): self
    {
        $clone = clone $this;
        $clone->POST = $POST;

        return $clone;
    }

    public function getFILES(): string
    {
        return $this->FILES;
    }

    public function withFILES(string $FILES): self
    {
        $clone = clone $this;
        $clone->FILES = $FILES;

        return $clone;
    }

    public function getREQUEST(): string
    {
        return $this->REQUEST;
    }

    public function withREQUEST(string $REQUEST): self
    {
        $clone = clone $this;
        $clone->REQUEST = $REQUEST;

        return $clone;
    }

    public function getSESSION(): string
    {
        return $this->SESSION;
    }

    public function withSESSION(string $SESSION): self
    {
        $clone = clone $this;
        $clone->SESSION = $SESSION;

        return $clone;
    }

    public function getENV(): string
    {
        return $this->ENV;
    }

    public function withENV(string $ENV): self
    {
        $clone = clone $this;
        $clone->ENV = $ENV;

        return $clone;
    }

    public function getCOOKIE(): string
    {
        return $this->COOKIE;
    }

    public function withCOOKIE(string $COOKIE): self
    {
        $clone = clone $this;
        $clone->COOKIE = $COOKIE;

        return $clone;
    }

    public function getPhpErrormsg(): string
    {
        return $this->phpErrormsg;
    }

    public function withPhpErrormsg(string $phpErrormsg): self
    {
        $clone = clone $this;
        $clone->phpErrormsg = $phpErrormsg;

        return $clone;
    }

    public function getHttpResponseHeader(): string
    {
        return $this->httpResponseHeader;
    }

    public function withHttpResponseHeader(string $httpResponseHeader): self
    {
        $clone = clone $this;
        $clone->httpResponseHeader = $httpResponseHeader;

        return $clone;
    }

    public function getArgc(): string
    {
        return $this->argc;
    }

    public function withArgc(string $_argc): self
    {
        $clone = clone $this;
        $clone->argc = $_argc;

        return $clone;
    }

    public function getArgv(): string
    {
        return $this->argv;
    }

    public function withArgv(string $_argv): self
    {
        $clone = clone $this;
        $clone->argv = $_argv;

        return $clone;
    }

    public function getInput(): string
    {
        return $this->input;
    }

    public function withInput(string $_input): self
    {
        $clone = clone $this;
        $clone->input = $_input;

        return $clone;
    }

    public function getValidate(): ?string
    {
        return $this->validate ?? null;
    }

    public function withValidate(string $_validate): self
    {
        $clone = clone $this;
        $clone->validate = $_validate;

        return $clone;
    }

    public function withoutValidate(): self
    {
        $clone = clone $this;
        unset($clone->validate);

        return $clone;
    }

    public function getMaterializeDefaults(): ?string
    {
        return $this->materializeDefaults ?? null;
    }

    public function withMaterializeDefaults(?string $_materializeDefaults): self
    {
        $clone = clone $this;
        $clone->materializeDefaults = $_materializeDefaults;
        $clone->_providedOptionals['materializeDefaults'] = true;

        return $clone;
    }

    public function withoutMaterializeDefaults(): self
    {
        $clone = clone $this;
        unset($clone->materializeDefaults);
        unset($clone->_providedOptionals['materializeDefaults']);

        return $clone;
    }

    public function getObj(): string
    {
        return $this->obj;
    }

    public function withObj(string $_obj): self
    {
        $clone = clone $this;
        $clone->obj = $_obj;

        return $clone;
    }

    public function getIncludeDefaults(): string
    {
        return $this->includeDefaults;
    }

    public function withIncludeDefaults(string $_includeDefaults): self
    {
        $clone = clone $this;
        $clone->includeDefaults = $_includeDefaults;

        return $clone;
    }

    public function getTestObj(): ?MyClassTestObj
    {
        return $this->testObj ?? null;
    }

    public function withTestObj(MyClassTestObj $testObj): self
    {
        $clone = clone $this;
        $clone->testObj = $testObj;

        return $clone;
    }

    public function withoutTestObj(): self
    {
        $clone = clone $this;
        unset($clone->testObj);

        return $clone;
    }

    public function getFromInput(): string
    {
        return $this->fromInput;
    }

    public function withFromInput(string $fromInput): self
    {
        $clone = clone $this;
        $clone->fromInput = $fromInput;

        return $clone;
    }

    public function getToArray(): string
    {
        return $this->toArray;
    }

    public function withToArray(string $toArray): self
    {
        $clone = clone $this;
        $clone->toArray = $toArray;

        return $clone;
    }

    public function getToStdClass(): string
    {
        return $this->toStdClass;
    }

    public function withToStdClass(string $toStdClass): self
    {
        $clone = clone $this;
        $clone->toStdClass = $toStdClass;

        return $clone;
    }

    public function getValidateInput(): string
    {
        return $this->validateInput;
    }

    public function withValidateInput(string $validateInput): self
    {
        $clone = clone $this;
        $clone->validateInput = $validateInput;

        return $clone;
    }

    public function getSchema1(): string
    {
        return $this->_schema_1;
    }

    public function withSchema1(string $_schema_1): self
    {
        $clone = clone $this;
        $clone->_schema_1 = $_schema_1;

        return $clone;
    }

    public function getSchema(): string
    {
        return $this->schema;
    }

    public function withSchema(string $schema): self
    {
        $clone = clone $this;
        $clone->schema = $schema;

        return $clone;
    }

    public function getDefaults1(): string
    {
        return $this->_defaults_1;
    }

    public function withDefaults1(string $_defaults_1): self
    {
        $clone = clone $this;
        $clone->_defaults_1 = $_defaults_1;

        return $clone;
    }

    public function getDefaults(): string
    {
        return $this->defaults;
    }

    public function withDefaults(string $defaults): self
    {
        $clone = clone $this;
        $clone->defaults = $defaults;

        return $clone;
    }

    public function getProvidedOptionals(): string
    {
        return $this->providedOptionals;
    }

    public function withProvidedOptionals(string $providedOptionals): self
    {
        $clone = clone $this;
        $clone->providedOptionals = $providedOptionals;

        return $clone;
    }

    public function getProvidedOptionals1(): ?string
    {
        return $this->_providedOptionals_1 ?? null;
    }

    public function withProvidedOptionals1(string $_providedOptionals_1): self
    {
        $clone = clone $this;
        $clone->_providedOptionals_1 = $_providedOptionals_1;

        return $clone;
    }

    public function withoutProvidedOptionals1(): self
    {
        $clone = clone $this;
        unset($clone->_providedOptionals_1);

        return $clone;
    }

    public function getClone(): string
    {
        return $this->clone;
    }

    public function withClone(string $_clone): self
    {
        $clone = clone $this;
        $clone->clone = $_clone;

        return $clone;
    }

    public function get_Clone(): string
    {
        return $this->_clone;
    }

    public function with_Clone(string $_clone_1): self
    {
        $clone = clone $this;
        $clone->_clone = $_clone_1;

        return $clone;
    }

    public function getConstruct(): string
    {
        return $this->construct;
    }

    public function withConstruct(string $construct): self
    {
        $clone = clone $this;
        $clone->construct = $construct;

        return $clone;
    }

    public function getDestruct(): string
    {
        return $this->destruct;
    }

    public function withDestruct(string $destruct): self
    {
        $clone = clone $this;
        $clone->destruct = $destruct;

        return $clone;
    }

    public function get_Get(): string
    {
        return $this->get;
    }

    public function with_Get(string $get): self
    {
        $clone = clone $this;
        $clone->get = $get;

        return $clone;
    }

    public function getSet(): string
    {
        return $this->set;
    }

    public function withSet(string $set): self
    {
        $clone = clone $this;
        $clone->set = $set;

        return $clone;
    }

    public function getCall(): string
    {
        return $this->call;
    }

    public function withCall(string $call): self
    {
        $clone = clone $this;
        $clone->call = $call;

        return $clone;
    }

    public function getIsset(): string
    {
        return $this->isset;
    }

    public function withIsset(string $isset): self
    {
        $clone = clone $this;
        $clone->isset = $isset;

        return $clone;
    }

    public function getUnset(): string
    {
        return $this->unset;
    }

    public function withUnset(string $unset): self
    {
        $clone = clone $this;
        $clone->unset = $unset;

        return $clone;
    }

    public function getSleep(): string
    {
        return $this->sleep;
    }

    public function withSleep(string $sleep): self
    {
        $clone = clone $this;
        $clone->sleep = $sleep;

        return $clone;
    }

    public function getWakeup(): string
    {
        return $this->wakeup;
    }

    public function withWakeup(string $wakeup): self
    {
        $clone = clone $this;
        $clone->wakeup = $wakeup;

        return $clone;
    }

    public function getToString(): string
    {
        return $this->toString;
    }

    public function withToString(string $toString): self
    {
        $clone = clone $this;
        $clone->toString = $toString;

        return $clone;
    }

    public function getInvoke(): string
    {
        return $this->invoke;
    }

    public function withInvoke(string $invoke): self
    {
        $clone = clone $this;
        $clone->invoke = $invoke;

        return $clone;
    }

    public function getDebugInfo(): string
    {
        return $this->debugInfo;
    }

    public function withDebugInfo(string $debugInfo): self
    {
        $clone = clone $this;
        $clone->debugInfo = $debugInfo;

        return $clone;
    }

    public function get_Files(): string
    {
        return $this->files;
    }

    public function with_Files(string $files): self
    {
        $clone = clone $this;
        $clone->files = $files;

        return $clone;
    }

    public function getThis(): string
    {
        return $this->_this;
    }

    public function withThis(string $_this): self
    {
        $clone = clone $this;
        $clone->_this = $_this;

        return $clone;
    }

    /**
     * @return MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string|null
     */
    public function getEnsureArgs1()
    {
        return $this->ensureArgs1 ?? null;
    }

    /**
     * @param MyClassEnsureArgs1Alternative1|MyClassEnsureArgs1Alternative2|string $ensureArgs1
     */
    public function withEnsureArgs1($ensureArgs1, bool $validate = true): self
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

    public function withoutEnsureArgs1(): self
    {
        $clone = clone $this;
        unset($clone->ensureArgs1);

        return $clone;
    }

    public function getEnsureArgs2(): ?MyClassEnsureArgs2
    {
        return $this->ensureArgs2 ?? null;
    }

    public function withEnsureArgs2(MyClassEnsureArgs2 $ensureArgs2): self
    {
        $clone = clone $this;
        $clone->ensureArgs2 = $ensureArgs2;

        return $clone;
    }

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

    public function withoutEnsureArgs3(): self
    {
        $clone = clone $this;
        unset($clone->ensureArgs3);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @param bool $materializeDefaults Apply defaults defined in schema when missing
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, bool $validate = true, bool $materializeDefaults = false): MyClass
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

        $_providedOptionals = [];
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
        $_obj = $input->{'obj'};
        $_includeDefaults = $input->{'includeDefaults'};
        $fromInput = $input->{'fromInput'};
        $toArray = $input->{'toArray'};
        $toStdClass = $input->{'toStdClass'};
        $validateInput = $input->{'validateInput'};
        $_schema_1 = $input->{'_schema'};
        $schema = $input->{'schema'};
        $_defaults_1 = $input->{'_defaults'};
        $defaults = $input->{'defaults'};
        $providedOptionals = $input->{'_providedOptionals'};
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
        $_validate = isset($input->{'validate'}) ? $input->{'validate'} : null;
        $_materializeDefaults = null;
        if (property_exists($input, 'materializeDefaults')) {
            $_materializeDefaults = $input->{'materializeDefaults'};
            $_providedOptionals['materializeDefaults'] = true;
        }
        $testObj = isset($input->{'testObj'})
            ? MyClassTestObj::fromInput($input->{'testObj'}, $validate, $materializeDefaults)
            : null;
        $_providedOptionals_1 = isset($input->{'__providedOptionals'}) ? $input->{'__providedOptionals'} : null;
        $ensureArgs1 = isset($input->{'ensureArgs1'})
            ? (((is_object($input->{'ensureArgs1'}) || is_array($input->{'ensureArgs1'})) && MyClassEnsureArgs1Alternative1::validateInput($input->{'ensureArgs1'}, true))
                ? MyClassEnsureArgs1Alternative1::fromInput($input->{'ensureArgs1'}, $validate, $materializeDefaults)
                : (((is_object($input->{'ensureArgs1'}) || is_array($input->{'ensureArgs1'})) && MyClassEnsureArgs1Alternative2::validateInput($input->{'ensureArgs1'}, true))
                    ? MyClassEnsureArgs1Alternative2::fromInput($input->{'ensureArgs1'}, $validate, $materializeDefaults)
                    : $input->{'ensureArgs1'}
                )
            )
            : null;
        $ensureArgs2 = isset($input->{'ensureArgs2'})
            ? MyClassEnsureArgs2::fromInput($input->{'ensureArgs2'}, $validate, $materializeDefaults)
            : null;
        $ensureArgs3 = isset($input->{'ensureArgs3'})
            ? array_map(
                fn ($i): MyClassEnsureArgs3Item => MyClassEnsureArgs3Item::fromInput($i, $validate, $materializeDefaults),
                $input->{'ensureArgs3'},
            )
            : null;

        $obj = new self(
            $_GLOBALS_1,
            $_GLOBALS,
            $GLOBALS1,
            $SERVER,
            $GET,
            $POST,
            $FILES,
            $REQUEST,
            $SESSION,
            $ENV,
            $COOKIE,
            $phpErrormsg,
            $httpResponseHeader,
            $_argc,
            $_argv,
            $_input,
            $_obj,
            $_includeDefaults,
            $fromInput,
            $toArray,
            $toStdClass,
            $validateInput,
            $_schema_1,
            $schema,
            $_defaults_1,
            $defaults,
            $providedOptionals,
            $_clone,
            $_clone_1,
            $construct,
            $destruct,
            $get,
            $set,
            $call,
            $isset,
            $unset,
            $sleep,
            $wakeup,
            $toString,
            $invoke,
            $debugInfo,
            $files,
            $_this,
            $_validate,
            $_materializeDefaults,
            $testObj,
            $_providedOptionals_1,
            $ensureArgs1,
            $ensureArgs2,
            $ensureArgs3
        );
        $obj->_providedOptionals = $_providedOptionals;

        $_additionalProperties = array_diff_key(get_object_vars($input), self::$_namesMap);
        if (!empty($_additionalProperties)) {
            $obj->_additionalProperties = (object) $_additionalProperties;
        }

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return array Converted array
     */
    public function toArray(bool $includeDefaults = false): array
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

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
            $output['materializeDefaults'] = $this->materializeDefaults;
        }
        $output['obj'] = $this->obj;
        $output['includeDefaults'] = $this->includeDefaults;
        if (isset($this->testObj)) {
            $output['testObj'] = $this->testObj->toArray($includeDefaults);
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
            if ($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative1
                || $this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative2
            ) {
                $output['ensureArgs1'] = $this->ensureArgs1->toArray($includeDefaults);
            } else {
                $output['ensureArgs1'] = $this->ensureArgs1;
            }
        }
        if (isset($this->ensureArgs2)) {
            $output['ensureArgs2'] = $this->ensureArgs2->toArray($includeDefaults);
        }
        if (isset($this->ensureArgs3)) {
            $output['ensureArgs3'] = array_map(fn (MyClassEnsureArgs3Item $i) => $i->toArray($includeDefaults), $this->ensureArgs3);
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
        $output = $this->_additionalProperties;

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
            $output->{'materializeDefaults'} = $this->materializeDefaults;
        }
        $output->{'obj'} = $this->obj;
        $output->{'includeDefaults'} = $this->includeDefaults;
        if (isset($this->testObj)) {
            $output->{'testObj'} = $this->testObj->toStdClass($includeDefaults);
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
            if ($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative1
                || $this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative2
            ) {
                $output->{'ensureArgs1'} = $this->ensureArgs1->toStdClass($includeDefaults);
            } else {
                $output->{'ensureArgs1'} = $this->ensureArgs1;
            }
        }
        if (isset($this->ensureArgs2)) {
            $output->{'ensureArgs2'} = $this->ensureArgs2->toStdClass($includeDefaults);
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
     * Validates the current instance against its schema
     *
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
     * @throws \InvalidArgumentException
     */
    public function validate(bool $return = false): bool
    {
        return self::validateInput($this->toStdClass(), $return);
    }

    /**
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
     * @throws \InvalidArgumentException
     */
    public static function validateInput($input, bool $return = false): bool
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(
                fn (array $e): string => ($e["property"] ? $e["property"] . ": " : "") . $e["message"],
                $validator->getErrors(),
            );
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        $this->_additionalProperties = json_decode(json_encode($this->_additionalProperties));

        if (isset($this->testObj)) {
            $this->testObj = clone $this->testObj;
        }
        if (isset($this->ensureArgs1)) {
            $this->ensureArgs1 = (($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative1
                || $this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative2
            )
                ? clone $this->ensureArgs1
                : $this->ensureArgs1
            );
        }
        if (isset($this->ensureArgs2)) {
            $this->ensureArgs2 = clone $this->ensureArgs2;
        }
        if (isset($this->ensureArgs3)) {
            $this->ensureArgs3 = array_map(fn (MyClassEnsureArgs3Item $i) => clone $i, $this->ensureArgs3);
        }
    }

    /**
     * Checks if an optional nullable property was explicitly set.
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema).
     * @throws \InvalidArgumentException If property with that name doesn't exist.
     */
    public function isOptionalProvided(string $propertyName): bool
    {
        if (!array_key_exists($propertyName, self::$_namesMap)) {
            throw new \InvalidArgumentException("Unknown property: {$propertyName}");
        }
        return
            array_key_exists($propertyName, $this->_providedOptionals)
            || isset($this->{ self::$_namesMap[$propertyName] });
    }
}
