<?php

declare(strict_types=1);

namespace Ns\PropTypeIsSingleTypeArray_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'required' => [
            'foo',
            'bar',
            'baz',
            'qux',
            'quux',
            'thud',
            'grox',
            'nullFoo',
            'nullBar',
            'nullBaz',
            'nullQux',
            'nullQuux',
            'nullThud',
            'nullGrox',
        ],
        'properties' => [
            'foo' => [
                'type' => [
                    'string',
                ],
            ],
            'bar' => [
                'type' => [
                    'number',
                ],
            ],
            'baz' => [
                'type' => [
                    'integer',
                ],
            ],
            'qux' => [
                'type' => [
                    'boolean',
                ],
            ],
            'quux' => [
                'type' => [
                    'object',
                ],
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'thud' => [
                'type' => [
                    'array',
                ],
                'items' => [
                    'type' => 'string',
                ],
            ],
            'grox' => [
                'type' => [
                    'null',
                ],
            ],
            'nullFoo' => [
                'type' => [
                    'string',
                    'null',
                ],
            ],
            'nullBar' => [
                'type' => [
                    'number',
                    'null',
                ],
            ],
            'nullBaz' => [
                'type' => [
                    'integer',
                    'null',
                ],
            ],
            'nullQux' => [
                'type' => [
                    'boolean',
                    'null',
                ],
            ],
            'nullQuux' => [
                'type' => [
                    'object',
                    'null',
                ],
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'nullThud' => [
                'type' => [
                    'array',
                    'null',
                ],
                'items' => [
                    'type' => 'string',
                ],
            ],
            'optFoo' => [
                'type' => [
                    'string',
                ],
            ],
            'optBar' => [
                'type' => [
                    'number',
                ],
            ],
            'optBaz' => [
                'type' => [
                    'integer',
                ],
            ],
            'optQux' => [
                'type' => [
                    'boolean',
                ],
            ],
            'optQuux' => [
                'type' => [
                    'object',
                ],
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'optThud' => [
                'type' => [
                    'array',
                ],
                'items' => [
                    'type' => 'string',
                ],
            ],
            'optGrox' => [
                'type' => [
                    'null',
                ],
            ],
            'optNullFoo' => [
                'type' => [
                    'string',
                    'null',
                ],
            ],
            'optNullBar' => [
                'type' => [
                    'number',
                    'null',
                ],
            ],
            'optNullBaz' => [
                'type' => [
                    'integer',
                    'null',
                ],
            ],
            'optNullQux' => [
                'type' => [
                    'boolean',
                    'null',
                ],
            ],
            'optNullQuux' => [
                'type' => [
                    'object',
                    'null',
                ],
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'optNullThud' => [
                'type' => [
                    'array',
                    'null',
                ],
                'items' => [
                    'type' => 'string',
                ],
            ],
        ],
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_providedOptionals = [];

    private string $foo;

    private int|float $bar;

    private int $baz;

    private bool $qux;

    private MyClassQuux $quux;

    /**
     * @var string[]
     */
    private array $thud;

    /**
     * @var null
     */
    private $grox;

    private ?string $nullFoo;

    private int|float|null $nullBar;

    private ?int $nullBaz;

    private ?bool $nullQux;

    private ?MyClassNullQuux $nullQuux;

    /**
     * @var string[]|null
     */
    private ?array $nullThud;

    private ?string $optFoo = null;

    private int|float|null $optBar = null;

    private ?int $optBaz = null;

    private ?bool $optQux = null;

    private ?MyClassOptQuux $optQuux = null;

    /**
     * @var string[]|null
     */
    private ?array $optThud = null;

    /**
     * @var null
     */
    private $optGrox = null;

    private ?string $optNullFoo = null;

    private int|float|null $optNullBar = null;

    private ?int $optNullBaz = null;

    private ?bool $optNullQux = null;

    private ?MyClassOptNullQuux $optNullQuux = null;

    /**
     * @var string[]|null
     */
    private ?array $optNullThud = null;

    /**
     * @param string[] $thud
     * @param null $grox
     * @param string[]|null $nullThud
     * @param string[]|null $optThud
     * @param null $optGrox
     * @param string[]|null $optNullThud
     */
    public function __construct(string $foo, int|float $bar, int $baz, bool $qux, MyClassQuux $quux, array $thud, $grox, ?string $nullFoo, int|float|null $nullBar, ?int $nullBaz, ?bool $nullQux, ?MyClassNullQuux $nullQuux, ?array $nullThud, ?string $optFoo = null, int|float|null $optBar = null, ?int $optBaz = null, ?bool $optQux = null, ?MyClassOptQuux $optQuux = null, ?array $optThud = null, $optGrox = null, ?string $optNullFoo = null, int|float|null $optNullBar = null, ?int $optNullBaz = null, ?bool $optNullQux = null, ?MyClassOptNullQuux $optNullQuux = null, ?array $optNullThud = null)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->qux = $qux;
        $this->quux = $quux;
        $this->thud = $thud;
        $this->grox = $grox;
        $this->nullFoo = $nullFoo;
        $this->nullBar = $nullBar;
        $this->nullBaz = $nullBaz;
        $this->nullQux = $nullQux;
        $this->nullQuux = $nullQuux;
        $this->nullThud = $nullThud;
        $this->optFoo = $optFoo;
        $this->optBar = $optBar;
        $this->optBaz = $optBaz;
        $this->optQux = $optQux;
        $this->optQuux = $optQuux;
        $this->optThud = $optThud;
        $this->optGrox = $optGrox;
        $this->optNullFoo = $optNullFoo;
        $this->optNullBar = $optNullBar;
        $this->optNullBaz = $optNullBaz;
        $this->optNullQux = $optNullQux;
        $this->optNullQuux = $optNullQuux;
        $this->optNullThud = $optNullThud;
    }

    public function getFoo(): string
    {
        return $this->foo;
    }

    public function withFoo(string $foo): self
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    public function getBar(): int|float
    {
        return $this->bar;
    }

    public function withBar(int|float $bar): self
    {
        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    public function getBaz(): int
    {
        return $this->baz;
    }

    public function withBaz(int $baz): self
    {
        $clone = clone $this;
        $clone->baz = $baz;

        return $clone;
    }

    public function getQux(): bool
    {
        return $this->qux;
    }

    public function withQux(bool $qux): self
    {
        $clone = clone $this;
        $clone->qux = $qux;

        return $clone;
    }

    public function getQuux(): MyClassQuux
    {
        return $this->quux;
    }

    public function withQuux(MyClassQuux $quux): self
    {
        $clone = clone $this;
        $clone->quux = $quux;

        return $clone;
    }

    /**
     * @return string[]
     */
    public function getThud(): array
    {
        return $this->thud;
    }

    /**
     * @param string[] $thud
     */
    public function withThud(array $thud, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($thud, self::$_schema['properties']['thud']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->thud = $thud;

        return $clone;
    }

    /**
     * @return null
     */
    public function getGrox()
    {
        return $this->grox;
    }

    /**
     * @param null $grox
     */
    public function withGrox($grox, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($grox, self::$_schema['properties']['grox']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->grox = $grox;

        return $clone;
    }

    public function getNullFoo(): ?string
    {
        return $this->nullFoo;
    }

    public function withNullFoo(?string $nullFoo): self
    {
        $clone = clone $this;
        $clone->nullFoo = $nullFoo;

        return $clone;
    }

    public function getNullBar(): int|float|null
    {
        return $this->nullBar;
    }

    public function withNullBar(int|float|null $nullBar): self
    {
        $clone = clone $this;
        $clone->nullBar = $nullBar;

        return $clone;
    }

    public function getNullBaz(): ?int
    {
        return $this->nullBaz;
    }

    public function withNullBaz(?int $nullBaz): self
    {
        $clone = clone $this;
        $clone->nullBaz = $nullBaz;

        return $clone;
    }

    public function getNullQux(): ?bool
    {
        return $this->nullQux;
    }

    public function withNullQux(?bool $nullQux): self
    {
        $clone = clone $this;
        $clone->nullQux = $nullQux;

        return $clone;
    }

    public function getNullQuux(): ?MyClassNullQuux
    {
        return $this->nullQuux;
    }

    public function withNullQuux(?MyClassNullQuux $nullQuux): self
    {
        $clone = clone $this;
        $clone->nullQuux = $nullQuux;

        return $clone;
    }

    /**
     * @return string[]|null
     */
    public function getNullThud(): ?array
    {
        return $this->nullThud;
    }

    /**
     * @param string[]|null $nullThud
     */
    public function withNullThud(?array $nullThud, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($nullThud, self::$_schema['properties']['nullThud']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->nullThud = $nullThud;

        return $clone;
    }

    public function getOptFoo(): ?string
    {
        return $this->optFoo;
    }

    public function withOptFoo(string $optFoo): self
    {
        $clone = clone $this;
        $clone->optFoo = $optFoo;

        return $clone;
    }

    public function withoutOptFoo(): self
    {
        $clone = clone $this;
        unset($clone->optFoo);

        return $clone;
    }

    public function getOptBar(): int|float|null
    {
        return $this->optBar;
    }

    public function withOptBar(int|float $optBar): self
    {
        $clone = clone $this;
        $clone->optBar = $optBar;

        return $clone;
    }

    public function withoutOptBar(): self
    {
        $clone = clone $this;
        unset($clone->optBar);

        return $clone;
    }

    public function getOptBaz(): ?int
    {
        return $this->optBaz;
    }

    public function withOptBaz(int $optBaz): self
    {
        $clone = clone $this;
        $clone->optBaz = $optBaz;

        return $clone;
    }

    public function withoutOptBaz(): self
    {
        $clone = clone $this;
        unset($clone->optBaz);

        return $clone;
    }

    public function getOptQux(): ?bool
    {
        return $this->optQux;
    }

    public function withOptQux(bool $optQux): self
    {
        $clone = clone $this;
        $clone->optQux = $optQux;

        return $clone;
    }

    public function withoutOptQux(): self
    {
        $clone = clone $this;
        unset($clone->optQux);

        return $clone;
    }

    public function getOptQuux(): ?MyClassOptQuux
    {
        return $this->optQuux;
    }

    public function withOptQuux(MyClassOptQuux $optQuux): self
    {
        $clone = clone $this;
        $clone->optQuux = $optQuux;

        return $clone;
    }

    public function withoutOptQuux(): self
    {
        $clone = clone $this;
        unset($clone->optQuux);

        return $clone;
    }

    /**
     * @return string[]|null
     */
    public function getOptThud(): ?array
    {
        return $this->optThud;
    }

    /**
     * @param string[] $optThud
     */
    public function withOptThud(array $optThud, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optThud, self::$_schema['properties']['optThud']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optThud = $optThud;

        return $clone;
    }

    public function withoutOptThud(): self
    {
        $clone = clone $this;
        unset($clone->optThud);

        return $clone;
    }

    /**
     * @return null
     */
    public function getOptGrox()
    {
        return $this->optGrox;
    }

    /**
     * @param null $optGrox
     */
    public function withOptGrox($optGrox, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optGrox, self::$_schema['properties']['optGrox']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optGrox = $optGrox;
        $clone->_providedOptionals['optGrox'] = true;

        return $clone;
    }

    public function withoutOptGrox(): self
    {
        $clone = clone $this;
        unset($clone->optGrox);
        unset($clone->_providedOptionals['optGrox']);

        return $clone;
    }

    public function getOptNullFoo(): ?string
    {
        return $this->optNullFoo;
    }

    public function withOptNullFoo(?string $optNullFoo): self
    {
        $clone = clone $this;
        $clone->optNullFoo = $optNullFoo;
        $clone->_providedOptionals['optNullFoo'] = true;

        return $clone;
    }

    public function withoutOptNullFoo(): self
    {
        $clone = clone $this;
        unset($clone->optNullFoo);
        unset($clone->_providedOptionals['optNullFoo']);

        return $clone;
    }

    public function getOptNullBar(): int|float|null
    {
        return $this->optNullBar;
    }

    public function withOptNullBar(int|float|null $optNullBar): self
    {
        $clone = clone $this;
        $clone->optNullBar = $optNullBar;
        $clone->_providedOptionals['optNullBar'] = true;

        return $clone;
    }

    public function withoutOptNullBar(): self
    {
        $clone = clone $this;
        unset($clone->optNullBar);
        unset($clone->_providedOptionals['optNullBar']);

        return $clone;
    }

    public function getOptNullBaz(): ?int
    {
        return $this->optNullBaz;
    }

    public function withOptNullBaz(?int $optNullBaz): self
    {
        $clone = clone $this;
        $clone->optNullBaz = $optNullBaz;
        $clone->_providedOptionals['optNullBaz'] = true;

        return $clone;
    }

    public function withoutOptNullBaz(): self
    {
        $clone = clone $this;
        unset($clone->optNullBaz);
        unset($clone->_providedOptionals['optNullBaz']);

        return $clone;
    }

    public function getOptNullQux(): ?bool
    {
        return $this->optNullQux;
    }

    public function withOptNullQux(?bool $optNullQux): self
    {
        $clone = clone $this;
        $clone->optNullQux = $optNullQux;
        $clone->_providedOptionals['optNullQux'] = true;

        return $clone;
    }

    public function withoutOptNullQux(): self
    {
        $clone = clone $this;
        unset($clone->optNullQux);
        unset($clone->_providedOptionals['optNullQux']);

        return $clone;
    }

    public function getOptNullQuux(): ?MyClassOptNullQuux
    {
        return $this->optNullQuux;
    }

    public function withOptNullQuux(?MyClassOptNullQuux $optNullQuux): self
    {
        $clone = clone $this;
        $clone->optNullQuux = $optNullQuux;
        $clone->_providedOptionals['optNullQuux'] = true;

        return $clone;
    }

    public function withoutOptNullQuux(): self
    {
        $clone = clone $this;
        unset($clone->optNullQuux);
        unset($clone->_providedOptionals['optNullQuux']);

        return $clone;
    }

    /**
     * @return string[]|null
     */
    public function getOptNullThud(): ?array
    {
        return $this->optNullThud;
    }

    /**
     * @param string[]|null $optNullThud
     */
    public function withOptNullThud(?array $optNullThud, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optNullThud, self::$_schema['properties']['optNullThud']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optNullThud = $optNullThud;
        $clone->_providedOptionals['optNullThud'] = true;

        return $clone;
    }

    public function withoutOptNullThud(): self
    {
        $clone = clone $this;
        unset($clone->optNullThud);
        unset($clone->_providedOptionals['optNullThud']);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $__providedOptionals = [];
        $foo = $input->{'foo'};
        $bar = (str_contains((string)$input->{'bar'}, '.') ? (float)$input->{'bar'} : (int)$input->{'bar'});
        $baz = (int)$input->{'baz'};
        $qux = (bool)$input->{'qux'};
        $quux = MyClassQuux::fromInput($input->{'quux'}, $validate);
        $thud = $input->{'thud'};
        $grox = $input->{'grox'};
        $nullFoo = $input->{'nullFoo'};
        $nullBar = ($input->{'nullBar'} !== null ? (str_contains((string)$input->{'nullBar'}, '.') ? (float)$input->{'nullBar'} : (int)$input->{'nullBar'}) : null);
        $nullBaz = ($input->{'nullBaz'} !== null ? (int)$input->{'nullBaz'} : null);
        $nullQux = ($input->{'nullQux'} !== null ? (bool)$input->{'nullQux'} : null);
        $nullQuux = ($input->{'nullQuux'} !== null ? MyClassNullQuux::fromInput($input->{'nullQuux'}, $validate) : null);
        $nullThud = ($input->{'nullThud'} !== null ? $input->{'nullThud'} : null);
        $optFoo = isset($input->{'optFoo'}) ? $input->{'optFoo'} : null;
        $optBar = isset($input->{'optBar'}) ? $input->{'optBar'} : null;
        $optBaz = isset($input->{'optBaz'}) ? $input->{'optBaz'} : null;
        $optQux = isset($input->{'optQux'}) ? $input->{'optQux'} : null;
        $optQuux = isset($input->{'optQuux'}) ? MyClassOptQuux::fromInput($input->{'optQuux'}, $validate) : null;
        $optThud = isset($input->{'optThud'}) ? $input->{'optThud'} : null;
        $optGrox = null;
        if (property_exists($input, 'optGrox')) {
            $optGrox = ($input->{'optGrox'} !== null ? $input->{'optGrox'} : null);
            $__providedOptionals['optGrox'] = true;
        }
        $optNullFoo = null;
        if (property_exists($input, 'optNullFoo')) {
            $optNullFoo = ($input->{'optNullFoo'} !== null ? $input->{'optNullFoo'} : null);
            $__providedOptionals['optNullFoo'] = true;
        }
        $optNullBar = null;
        if (property_exists($input, 'optNullBar')) {
            $optNullBar = ($input->{'optNullBar'} !== null ? $input->{'optNullBar'} : null);
            $__providedOptionals['optNullBar'] = true;
        }
        $optNullBaz = null;
        if (property_exists($input, 'optNullBaz')) {
            $optNullBaz = ($input->{'optNullBaz'} !== null ? $input->{'optNullBaz'} : null);
            $__providedOptionals['optNullBaz'] = true;
        }
        $optNullQux = null;
        if (property_exists($input, 'optNullQux')) {
            $optNullQux = ($input->{'optNullQux'} !== null ? $input->{'optNullQux'} : null);
            $__providedOptionals['optNullQux'] = true;
        }
        $optNullQuux = null;
        if (property_exists($input, 'optNullQuux')) {
            $optNullQuux = ($input->{'optNullQuux'} !== null ? MyClassOptNullQuux::fromInput($input->{'optNullQuux'}, $validate) : null);
            $__providedOptionals['optNullQuux'] = true;
        }
        $optNullThud = null;
        if (property_exists($input, 'optNullThud')) {
            $optNullThud = ($input->{'optNullThud'} !== null ? $input->{'optNullThud'} : null);
            $__providedOptionals['optNullThud'] = true;
        }

        $obj = new self(
            $foo,
            $bar,
            $baz,
            $qux,
            $quux,
            $thud,
            $grox,
            $nullFoo,
            $nullBar,
            $nullBaz,
            $nullQux,
            $nullQuux,
            $nullThud,
            $optFoo,
            $optBar,
            $optBaz,
            $optQux,
            $optQuux,
            $optThud,
            $optGrox,
            $optNullFoo,
            $optNullBar,
            $optNullBaz,
            $optNullQux,
            $optNullQuux,
            $optNullThud
        );
        $obj->_providedOptionals = $__providedOptionals;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = [];
        $output['foo'] = $this->foo;
        $output['bar'] = $this->bar;
        $output['baz'] = $this->baz;
        $output['qux'] = $this->qux;
        $output['quux'] = $this->quux->toArray();
        $output['thud'] = $this->thud;
        $output['grox'] = $this->grox;
        $output['nullFoo'] = $this->nullFoo;
        $output['nullBar'] = $this->nullBar;
        $output['nullBaz'] = $this->nullBaz;
        $output['nullQux'] = $this->nullQux;
        $output['nullQuux'] = $this->nullQuux->toArray();
        $output['nullThud'] = $this->nullThud;
        if (isset($this->optFoo)) {
            $output['optFoo'] = $this->optFoo;
        }
        if (isset($this->optBar)) {
            $output['optBar'] = $this->optBar;
        }
        if (isset($this->optBaz)) {
            $output['optBaz'] = $this->optBaz;
        }
        if (isset($this->optQux)) {
            $output['optQux'] = $this->optQux;
        }
        if (isset($this->optQuux)) {
            $output['optQuux'] = $this->optQuux->toArray();
        }
        if (isset($this->optThud)) {
            $output['optThud'] = $this->optThud;
        }
        if (isset($this->optGrox) || array_key_exists('optGrox', $this->_providedOptionals)) {
            $output['optGrox'] = ($this->optGrox !== null) ? ($this->optGrox) : null;
        }
        if (isset($this->optNullFoo) || array_key_exists('optNullFoo', $this->_providedOptionals)) {
            $output['optNullFoo'] = ($this->optNullFoo !== null) ? ($this->optNullFoo) : null;
        }
        if (isset($this->optNullBar) || array_key_exists('optNullBar', $this->_providedOptionals)) {
            $output['optNullBar'] = ($this->optNullBar !== null) ? ($this->optNullBar) : null;
        }
        if (isset($this->optNullBaz) || array_key_exists('optNullBaz', $this->_providedOptionals)) {
            $output['optNullBaz'] = ($this->optNullBaz !== null) ? ($this->optNullBaz) : null;
        }
        if (isset($this->optNullQux) || array_key_exists('optNullQux', $this->_providedOptionals)) {
            $output['optNullQux'] = ($this->optNullQux !== null) ? ($this->optNullQux) : null;
        }
        if (isset($this->optNullQuux) || array_key_exists('optNullQuux', $this->_providedOptionals)) {
            $output['optNullQuux'] = ($this->optNullQuux !== null) ? ($this->optNullQuux->toArray()) : null;
        }
        if (isset($this->optNullThud) || array_key_exists('optNullThud', $this->_providedOptionals)) {
            $output['optNullThud'] = ($this->optNullThud !== null) ? ($this->optNullThud) : null;
        }

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @return \stdClass Converted object
     */
    public function toStdClass(): \stdClass
    {
        $output = new \stdClass();
        $output->{'foo'} = $this->foo;
        $output->{'bar'} = $this->bar;
        $output->{'baz'} = $this->baz;
        $output->{'qux'} = $this->qux;
        $output->{'quux'} = $this->quux->toStdClass();
        $output->{'thud'} = $this->thud;
        $output->{'grox'} = $this->grox;
        $output->{'nullFoo'} = $this->nullFoo;
        $output->{'nullBar'} = $this->nullBar;
        $output->{'nullBaz'} = $this->nullBaz;
        $output->{'nullQux'} = $this->nullQux;
        $output->{'nullQuux'} = $this->nullQuux->toStdClass();
        $output->{'nullThud'} = $this->nullThud;
        if (isset($this->optFoo)) {
            $output->{'optFoo'} = $this->optFoo;
        }
        if (isset($this->optBar)) {
            $output->{'optBar'} = $this->optBar;
        }
        if (isset($this->optBaz)) {
            $output->{'optBaz'} = $this->optBaz;
        }
        if (isset($this->optQux)) {
            $output->{'optQux'} = $this->optQux;
        }
        if (isset($this->optQuux)) {
            $output->{'optQuux'} = $this->optQuux->toStdClass();
        }
        if (isset($this->optThud)) {
            $output->{'optThud'} = $this->optThud;
        }
        if (isset($this->optGrox) || array_key_exists('optGrox', $this->_providedOptionals)) {
            $output->{'optGrox'} = ($this->optGrox !== null) ? ($this->optGrox) : null;
        }
        if (isset($this->optNullFoo) || array_key_exists('optNullFoo', $this->_providedOptionals)) {
            $output->{'optNullFoo'} = ($this->optNullFoo !== null) ? ($this->optNullFoo) : null;
        }
        if (isset($this->optNullBar) || array_key_exists('optNullBar', $this->_providedOptionals)) {
            $output->{'optNullBar'} = ($this->optNullBar !== null) ? ($this->optNullBar) : null;
        }
        if (isset($this->optNullBaz) || array_key_exists('optNullBaz', $this->_providedOptionals)) {
            $output->{'optNullBaz'} = ($this->optNullBaz !== null) ? ($this->optNullBaz) : null;
        }
        if (isset($this->optNullQux) || array_key_exists('optNullQux', $this->_providedOptionals)) {
            $output->{'optNullQux'} = ($this->optNullQux !== null) ? ($this->optNullQux) : null;
        }
        if (isset($this->optNullQuux) || array_key_exists('optNullQuux', $this->_providedOptionals)) {
            $output->{'optNullQuux'} = ($this->optNullQuux !== null) ? ($this->optNullQuux->toStdClass()) : null;
        }
        if (isset($this->optNullThud) || array_key_exists('optNullThud', $this->_providedOptionals)) {
            $output->{'optNullThud'} = ($this->optNullThud !== null) ? ($this->optNullThud) : null;
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
        $this->quux = clone $this->quux;
        $this->nullQuux = clone $this->nullQuux;
        if (isset($this->optQuux)) {
            $this->optQuux = clone $this->optQuux;
        }
        if (isset($this->optNullQuux)) {
            if (isset($this->optNullQuux)) {
                $this->optNullQuux = clone $this->optNullQuux;
            }
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
