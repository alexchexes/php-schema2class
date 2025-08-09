<?php

declare(strict_types=1);

namespace Ns\FallbackNamingPreserve_7_4;

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
        'GLOBALS_1' => 'GLOBALS_1',
        '_SERVER' => '_SERVER',
        '_GET' => '_GET',
        '_POST' => '_POST',
        '_FILES' => '_FILES',
        '_REQUEST' => '_REQUEST',
        '_SESSION' => '_SESSION',
        '_ENV' => '_ENV',
        '_COOKIE' => '_COOKIE',
        'php_errormsg' => 'php_errormsg',
        'http_response_header' => 'http_response_header',
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
        '_providedOptionals' => '_providedOptionals_1',
        '__providedOptionals' => '__providedOptionals',
        'clone' => 'clone',
        '__clone' => '__clone',
        '__construct' => '__construct',
        '__destruct' => '__destruct',
        '__get' => '__get',
        '__set' => '__set',
        '__call' => '__call',
        '__isset' => '__isset',
        '__unset' => '__unset',
        '__sleep' => '__sleep',
        '__wakeup' => '__wakeup',
        '__toString' => '__toString',
        '__invoke' => '__invoke',
        '__debugInfo' => '__debugInfo',
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

    private string $GLOBALS_1;

    private string $_SERVER;

    private string $_GET;

    private string $_POST;

    private string $_FILES;

    private string $_REQUEST;

    private string $_SESSION;

    private string $_ENV;

    private string $_COOKIE;

    private string $php_errormsg;

    private string $http_response_header;

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

    private string $_providedOptionals_1;

    private ?string $__providedOptionals = null;

    private string $clone;

    private string $__clone;

    private string $__construct;

    private string $__destruct;

    private string $__get;

    private string $__set;

    private string $__call;

    private string $__isset;

    private string $__unset;

    private string $__sleep;

    private string $__wakeup;

    private string $__toString;

    private string $__invoke;

    private string $__debugInfo;

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
    public function __construct(string $_GLOBALS_1, string $_GLOBALS, string $GLOBALS_1, string $_SERVER_1, string $_GET_1, string $_POST_1, string $_FILES_1, string $_REQUEST_1, string $_SESSION_1, string $_ENV_1, string $_COOKIE_1, string $_php_errormsg, string $_http_response_header, string $_argc, string $_argv, string $_input, string $_obj, string $_includeDefaults, string $fromInput, string $toArray, string $toStdClass, string $validateInput, string $_schema_1, string $schema, string $_defaults_1, string $defaults, string $_providedOptionals_1, string $_clone, string $__clone, string $__construct, string $__destruct, string $__get, string $__set, string $__call, string $__isset, string $__unset, string $__sleep, string $__wakeup, string $__toString, string $__invoke, string $__debugInfo, string $files, string $_this, ?string $_validate = null, ?string $_materializeDefaults = null, ?MyClassTestObj $testObj = null, ?string $__providedOptionals = null, $ensureArgs1 = null, ?MyClassEnsureArgs2 $ensureArgs2 = null, ?array $ensureArgs3 = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->_GLOBALS = $_GLOBALS_1;
        $this->GLOBALS = $_GLOBALS;
        $this->GLOBALS_1 = $GLOBALS_1;
        $this->_SERVER = $_SERVER_1;
        $this->_GET = $_GET_1;
        $this->_POST = $_POST_1;
        $this->_FILES = $_FILES_1;
        $this->_REQUEST = $_REQUEST_1;
        $this->_SESSION = $_SESSION_1;
        $this->_ENV = $_ENV_1;
        $this->_COOKIE = $_COOKIE_1;
        $this->php_errormsg = $_php_errormsg;
        $this->http_response_header = $_http_response_header;
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
        $this->_providedOptionals_1 = $_providedOptionals_1;
        $this->clone = $_clone;
        $this->__clone = $__clone;
        $this->__construct = $__construct;
        $this->__destruct = $__destruct;
        $this->__get = $__get;
        $this->__set = $__set;
        $this->__call = $__call;
        $this->__isset = $__isset;
        $this->__unset = $__unset;
        $this->__sleep = $__sleep;
        $this->__wakeup = $__wakeup;
        $this->__toString = $__toString;
        $this->__invoke = $__invoke;
        $this->__debugInfo = $__debugInfo;
        $this->files = $files;
        $this->_this = $_this;
        $this->validate = $_validate;
        if ($_materializeDefaults !== null) {
            $this->materializeDefaults = $_materializeDefaults;
            $this->_providedOptionals['materializeDefaults'] = true;
        };
        $this->testObj = $testObj;
        $this->__providedOptionals = $__providedOptionals;
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
        return $this->GLOBALS_1;
    }

    public function withGLOBALS1(string $GLOBALS_1): self
    {
        $clone = clone $this;
        $clone->GLOBALS_1 = $GLOBALS_1;

        return $clone;
    }

    public function get_SERVER(): string
    {
        return $this->_SERVER;
    }

    public function with_SERVER(string $_SERVER_1): self
    {
        $clone = clone $this;
        $clone->_SERVER = $_SERVER_1;

        return $clone;
    }

    public function get_GET(): string
    {
        return $this->_GET;
    }

    public function with_GET(string $_GET_1): self
    {
        $clone = clone $this;
        $clone->_GET = $_GET_1;

        return $clone;
    }

    public function get_POST(): string
    {
        return $this->_POST;
    }

    public function with_POST(string $_POST_1): self
    {
        $clone = clone $this;
        $clone->_POST = $_POST_1;

        return $clone;
    }

    public function get_FILES(): string
    {
        return $this->_FILES;
    }

    public function with_FILES(string $_FILES_1): self
    {
        $clone = clone $this;
        $clone->_FILES = $_FILES_1;

        return $clone;
    }

    public function get_REQUEST(): string
    {
        return $this->_REQUEST;
    }

    public function with_REQUEST(string $_REQUEST_1): self
    {
        $clone = clone $this;
        $clone->_REQUEST = $_REQUEST_1;

        return $clone;
    }

    public function get_SESSION(): string
    {
        return $this->_SESSION;
    }

    public function with_SESSION(string $_SESSION_1): self
    {
        $clone = clone $this;
        $clone->_SESSION = $_SESSION_1;

        return $clone;
    }

    public function get_ENV(): string
    {
        return $this->_ENV;
    }

    public function with_ENV(string $_ENV_1): self
    {
        $clone = clone $this;
        $clone->_ENV = $_ENV_1;

        return $clone;
    }

    public function get_COOKIE(): string
    {
        return $this->_COOKIE;
    }

    public function with_COOKIE(string $_COOKIE_1): self
    {
        $clone = clone $this;
        $clone->_COOKIE = $_COOKIE_1;

        return $clone;
    }

    public function getPhpErrormsg(): string
    {
        return $this->php_errormsg;
    }

    public function withPhpErrormsg(string $_php_errormsg): self
    {
        $clone = clone $this;
        $clone->php_errormsg = $_php_errormsg;

        return $clone;
    }

    public function getHttpResponseHeader(): string
    {
        return $this->http_response_header;
    }

    public function withHttpResponseHeader(string $_http_response_header): self
    {
        $clone = clone $this;
        $clone->http_response_header = $_http_response_header;

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

    public function get_Schema1(): string
    {
        return $this->_schema_1;
    }

    public function with_Schema1(string $_schema_1): self
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

    public function get_Defaults1(): string
    {
        return $this->_defaults_1;
    }

    public function with_Defaults1(string $_defaults_1): self
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

    public function get_ProvidedOptionals1(): string
    {
        return $this->_providedOptionals_1;
    }

    public function with_ProvidedOptionals1(string $_providedOptionals_1): self
    {
        $clone = clone $this;
        $clone->_providedOptionals_1 = $_providedOptionals_1;

        return $clone;
    }

    public function get__ProvidedOptionals(): ?string
    {
        return $this->__providedOptionals ?? null;
    }

    public function with__ProvidedOptionals(string $__providedOptionals): self
    {
        $clone = clone $this;
        $clone->__providedOptionals = $__providedOptionals;

        return $clone;
    }

    public function without__ProvidedOptionals(): self
    {
        $clone = clone $this;
        unset($clone->__providedOptionals);

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

    public function get__Clone(): string
    {
        return $this->__clone;
    }

    public function with__Clone(string $__clone): self
    {
        $clone = clone $this;
        $clone->__clone = $__clone;

        return $clone;
    }

    public function get__Construct(): string
    {
        return $this->__construct;
    }

    public function with__Construct(string $__construct): self
    {
        $clone = clone $this;
        $clone->__construct = $__construct;

        return $clone;
    }

    public function get__Destruct(): string
    {
        return $this->__destruct;
    }

    public function with__Destruct(string $__destruct): self
    {
        $clone = clone $this;
        $clone->__destruct = $__destruct;

        return $clone;
    }

    public function get__Get(): string
    {
        return $this->__get;
    }

    public function with__Get(string $__get): self
    {
        $clone = clone $this;
        $clone->__get = $__get;

        return $clone;
    }

    public function get__Set(): string
    {
        return $this->__set;
    }

    public function with__Set(string $__set): self
    {
        $clone = clone $this;
        $clone->__set = $__set;

        return $clone;
    }

    public function get__Call(): string
    {
        return $this->__call;
    }

    public function with__Call(string $__call): self
    {
        $clone = clone $this;
        $clone->__call = $__call;

        return $clone;
    }

    public function get__Isset(): string
    {
        return $this->__isset;
    }

    public function with__Isset(string $__isset): self
    {
        $clone = clone $this;
        $clone->__isset = $__isset;

        return $clone;
    }

    public function get__Unset(): string
    {
        return $this->__unset;
    }

    public function with__Unset(string $__unset): self
    {
        $clone = clone $this;
        $clone->__unset = $__unset;

        return $clone;
    }

    public function get__Sleep(): string
    {
        return $this->__sleep;
    }

    public function with__Sleep(string $__sleep): self
    {
        $clone = clone $this;
        $clone->__sleep = $__sleep;

        return $clone;
    }

    public function get__Wakeup(): string
    {
        return $this->__wakeup;
    }

    public function with__Wakeup(string $__wakeup): self
    {
        $clone = clone $this;
        $clone->__wakeup = $__wakeup;

        return $clone;
    }

    public function get__ToString(): string
    {
        return $this->__toString;
    }

    public function with__ToString(string $__toString): self
    {
        $clone = clone $this;
        $clone->__toString = $__toString;

        return $clone;
    }

    public function get__Invoke(): string
    {
        return $this->__invoke;
    }

    public function with__Invoke(string $__invoke): self
    {
        $clone = clone $this;
        $clone->__invoke = $__invoke;

        return $clone;
    }

    public function get__DebugInfo(): string
    {
        return $this->__debugInfo;
    }

    public function with__DebugInfo(string $__debugInfo): self
    {
        $clone = clone $this;
        $clone->__debugInfo = $__debugInfo;

        return $clone;
    }

    public function getFiles(): string
    {
        return $this->files;
    }

    public function withFiles(string $files): self
    {
        $clone = clone $this;
        $clone->files = $files;

        return $clone;
    }

    public function get_This(): string
    {
        return $this->_this;
    }

    public function with_This(string $_this): self
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
        $_providedOptionals_1 = $input->{'_providedOptionals'};
        $_clone = $input->{'clone'};
        $__clone = $input->{'__clone'};
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
        $files = $input->{'files'};
        $_this = $input->{'this'};
        $_validate = isset($input->{'validate'}) ? $input->{'validate'} : null;
        $_materializeDefaults = null;
        if (property_exists($input, 'materializeDefaults')) {
            $_materializeDefaults = ($input->{'materializeDefaults'} !== null ? $input->{'materializeDefaults'} : null);
            $_providedOptionals['materializeDefaults'] = true;
        }
        $testObj = isset($input->{'testObj'}) ? MyClassTestObj::fromInput($input->{'testObj'}, $validate, $materializeDefaults) : null;
        $__providedOptionals = isset($input->{'__providedOptionals'}) ? $input->{'__providedOptionals'} : null;
        $ensureArgs1 = isset($input->{'ensureArgs1'}) ? ((is_string($input->{'ensureArgs1'})) ? $input->{'ensureArgs1'} : (((MyClassEnsureArgs1Alternative2::validateInput($input->{'ensureArgs1'}, true)) ? MyClassEnsureArgs1Alternative2::fromInput($input->{'ensureArgs1'}, $validate, $materializeDefaults) : (((MyClassEnsureArgs1Alternative1::validateInput($input->{'ensureArgs1'}, true)) ? MyClassEnsureArgs1Alternative1::fromInput($input->{'ensureArgs1'}, $validate, $materializeDefaults) : (null)))))) : null;
        $ensureArgs2 = isset($input->{'ensureArgs2'}) ? MyClassEnsureArgs2::fromInput($input->{'ensureArgs2'}, $validate, $materializeDefaults) : null;
        $ensureArgs3 = isset($input->{'ensureArgs3'}) ? array_map(fn ($i): MyClassEnsureArgs3Item => MyClassEnsureArgs3Item::fromInput($i, $validate, $materializeDefaults), $input->{'ensureArgs3'}) : null;

        $obj = new self(
            $_GLOBALS_1,
            $_GLOBALS,
            $GLOBALS_1,
            $_SERVER_1,
            $_GET_1,
            $_POST_1,
            $_FILES_1,
            $_REQUEST_1,
            $_SESSION_1,
            $_ENV_1,
            $_COOKIE_1,
            $_php_errormsg,
            $_http_response_header,
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
            $_providedOptionals_1,
            $_clone,
            $__clone,
            $__construct,
            $__destruct,
            $__get,
            $__set,
            $__call,
            $__isset,
            $__unset,
            $__sleep,
            $__wakeup,
            $__toString,
            $__invoke,
            $__debugInfo,
            $files,
            $_this,
            $_validate,
            $_materializeDefaults,
            $testObj,
            $__providedOptionals,
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
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return array Converted array
     */
    public function toArray(bool $includeDefaults = false): array
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        $output['_GLOBALS'] = $this->_GLOBALS;
        $output['GLOBALS'] = $this->GLOBALS;
        $output['GLOBALS_1'] = $this->GLOBALS_1;
        $output['_SERVER'] = $this->_SERVER;
        $output['_GET'] = $this->_GET;
        $output['_POST'] = $this->_POST;
        $output['_FILES'] = $this->_FILES;
        $output['_REQUEST'] = $this->_REQUEST;
        $output['_SESSION'] = $this->_SESSION;
        $output['_ENV'] = $this->_ENV;
        $output['_COOKIE'] = $this->_COOKIE;
        $output['php_errormsg'] = $this->php_errormsg;
        $output['http_response_header'] = $this->http_response_header;
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
        $output['_providedOptionals'] = $this->_providedOptionals_1;
        if (isset($this->__providedOptionals)) {
            $output['__providedOptionals'] = $this->__providedOptionals;
        }
        $output['clone'] = $this->clone;
        $output['__clone'] = $this->__clone;
        $output['__construct'] = $this->__construct;
        $output['__destruct'] = $this->__destruct;
        $output['__get'] = $this->__get;
        $output['__set'] = $this->__set;
        $output['__call'] = $this->__call;
        $output['__isset'] = $this->__isset;
        $output['__unset'] = $this->__unset;
        $output['__sleep'] = $this->__sleep;
        $output['__wakeup'] = $this->__wakeup;
        $output['__toString'] = $this->__toString;
        $output['__invoke'] = $this->__invoke;
        $output['__debugInfo'] = $this->__debugInfo;
        $output['files'] = $this->files;
        $output['this'] = $this->_this;
        if (isset($this->ensureArgs1)) {
            if (($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative1) || ($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative2)) {
                $output['ensureArgs1'] = $this->ensureArgs1->toArray($includeDefaults);
            } else if ((is_string($this->ensureArgs1))) {
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
        $output->{'GLOBALS_1'} = $this->GLOBALS_1;
        $output->{'_SERVER'} = $this->_SERVER;
        $output->{'_GET'} = $this->_GET;
        $output->{'_POST'} = $this->_POST;
        $output->{'_FILES'} = $this->_FILES;
        $output->{'_REQUEST'} = $this->_REQUEST;
        $output->{'_SESSION'} = $this->_SESSION;
        $output->{'_ENV'} = $this->_ENV;
        $output->{'_COOKIE'} = $this->_COOKIE;
        $output->{'php_errormsg'} = $this->php_errormsg;
        $output->{'http_response_header'} = $this->http_response_header;
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
        $output->{'_providedOptionals'} = $this->_providedOptionals_1;
        if (isset($this->__providedOptionals)) {
            $output->{'__providedOptionals'} = $this->__providedOptionals;
        }
        $output->{'clone'} = $this->clone;
        $output->{'__clone'} = $this->__clone;
        $output->{'__construct'} = $this->__construct;
        $output->{'__destruct'} = $this->__destruct;
        $output->{'__get'} = $this->__get;
        $output->{'__set'} = $this->__set;
        $output->{'__call'} = $this->__call;
        $output->{'__isset'} = $this->__isset;
        $output->{'__unset'} = $this->__unset;
        $output->{'__sleep'} = $this->__sleep;
        $output->{'__wakeup'} = $this->__wakeup;
        $output->{'__toString'} = $this->__toString;
        $output->{'__invoke'} = $this->__invoke;
        $output->{'__debugInfo'} = $this->__debugInfo;
        $output->{'files'} = $this->files;
        $output->{'this'} = $this->_this;
        if (isset($this->ensureArgs1)) {
            if (($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative1) || ($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative2)) {
            $output->{'ensureArgs1'} = $this->ensureArgs1->toStdClass($includeDefaults);
            } else if ((is_string($this->ensureArgs1))) {
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
            $this->ensureArgs1 = (is_string($this->ensureArgs1) ? $this->ensureArgs1 : ($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative2 ? clone $this->ensureArgs1 : ($this->ensureArgs1 instanceof MyClassEnsureArgs1Alternative1 ? clone $this->ensureArgs1 : $this->ensureArgs1)));
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
