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

    /**
     * @var string
     */
    private string $foo;

    /**
     * @var int|float
     */
    private int|float $bar;

    /**
     * @var int
     */
    private int $baz;

    /**
     * @var bool
     */
    private bool $qux;

    /**
     * @var MyClassQuux
     */
    private MyClassQuux $quux;

    /**
     * @var string[]
     */
    private array $thud;

    /**
     * @var null
     */
    private $grox;

    /**
     * @var string|null
     */
    private ?string $nullFoo;

    /**
     * @var int|float|null
     */
    private int|float|null $nullBar;

    /**
     * @var int|null
     */
    private ?int $nullBaz;

    /**
     * @var bool|null
     */
    private ?bool $nullQux;

    /**
     * @var MyClassNullQuux|null
     */
    private ?MyClassNullQuux $nullQuux;

    /**
     * @var string[]|null
     */
    private ?array $nullThud;

    /**
     * @var string|null
     */
    private ?string $optFoo = null;

    /**
     * @var int|float|null
     */
    private int|float|null $optBar = null;

    /**
     * @var int|null
     */
    private ?int $optBaz = null;

    /**
     * @var bool|null
     */
    private ?bool $optQux = null;

    /**
     * @var MyClassOptQuux|null
     */
    private ?MyClassOptQuux $optQuux = null;

    /**
     * @var string[]|null
     */
    private ?array $optThud = null;

    /**
     * @var null
     */
    private $optGrox = null;

    /**
     * @var string|null
     */
    private ?string $optNullFoo = null;

    /**
     * @var int|float|null
     */
    private int|float|null $optNullBar = null;

    /**
     * @var int|null
     */
    private ?int $optNullBaz = null;

    /**
     * @var bool|null
     */
    private ?bool $optNullQux = null;

    /**
     * @var MyClassOptNullQuux|null
     */
    private ?MyClassOptNullQuux $optNullQuux = null;

    /**
     * @var string[]|null
     */
    private ?array $optNullThud = null;

    /**
     * @param string $foo
     * @param int|float $bar
     * @param int $baz
     * @param bool $qux
     * @param MyClassQuux $quux
     * @param string[] $thud
     * @param null $grox
     * @param string|null $nullFoo
     * @param int|float|null $nullBar
     * @param int|null $nullBaz
     * @param bool|null $nullQux
     * @param MyClassNullQuux|null $nullQuux
     * @param string[]|null $nullThud
     */
    public function __construct(string $foo, int|float $bar, int $baz, bool $qux, MyClassQuux $quux, array $thud, $grox, ?string $nullFoo, int|float|null $nullBar, ?int $nullBaz, ?bool $nullQux, ?MyClassNullQuux $nullQuux, ?array $nullThud)
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
    }

    /**
     * @return string
     */
    public function getFoo(): string
    {
        return $this->foo;
    }

    /**
     * @param string $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo(string $foo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$_schema['properties']['foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * @return int|float
     */
    public function getBar(): int|float
    {
        return $this->bar;
    }

    /**
     * @param int|float $bar
     * @return self
     * @param bool $validate
     */
    public function withBar(int|float $bar, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bar, self::$_schema['properties']['bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    /**
     * @return int
     */
    public function getBaz(): int
    {
        return $this->baz;
    }

    /**
     * @param int $baz
     * @return self
     * @param bool $validate
     */
    public function withBaz(int $baz, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($baz, self::$_schema['properties']['baz']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->baz = $baz;

        return $clone;
    }

    /**
     * @return bool
     */
    public function getQux(): bool
    {
        return $this->qux;
    }

    /**
     * @param bool $qux
     * @return self
     * @param bool $validate
     */
    public function withQux(bool $qux, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($qux, self::$_schema['properties']['qux']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->qux = $qux;

        return $clone;
    }

    /**
     * @return MyClassQuux
     */
    public function getQuux(): MyClassQuux
    {
        return $this->quux;
    }

    /**
     * @param MyClassQuux $quux
     * @return self
     */
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
     * @return self
     * @param bool $validate
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
     * @return self
     * @param bool $validate
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

    /**
     * @return string|null
     */
    public function getNullFoo(): ?string
    {
        return $this->nullFoo ?? null;
    }

    /**
     * @param string|null $nullFoo
     * @return self
     * @param bool $validate
     */
    public function withNullFoo(?string $nullFoo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($nullFoo, self::$_schema['properties']['nullFoo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->nullFoo = $nullFoo;

        return $clone;
    }

    /**
     * @return int|float|null
     */
    public function getNullBar(): int|float|null
    {
        return $this->nullBar;
    }

    /**
     * @param int|float|null $nullBar
     * @return self
     * @param bool $validate
     */
    public function withNullBar(int|float|null $nullBar, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($nullBar, self::$_schema['properties']['nullBar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->nullBar = $nullBar;

        return $clone;
    }

    /**
     * @return int|null
     */
    public function getNullBaz(): ?int
    {
        return $this->nullBaz ?? null;
    }

    /**
     * @param int|null $nullBaz
     * @return self
     * @param bool $validate
     */
    public function withNullBaz(?int $nullBaz, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($nullBaz, self::$_schema['properties']['nullBaz']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->nullBaz = $nullBaz;

        return $clone;
    }

    /**
     * @return bool|null
     */
    public function getNullQux(): ?bool
    {
        return $this->nullQux ?? null;
    }

    /**
     * @param bool|null $nullQux
     * @return self
     * @param bool $validate
     */
    public function withNullQux(?bool $nullQux, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($nullQux, self::$_schema['properties']['nullQux']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->nullQux = $nullQux;

        return $clone;
    }

    /**
     * @return MyClassNullQuux|null
     */
    public function getNullQuux(): ?MyClassNullQuux
    {
        return $this->nullQuux ?? null;
    }

    /**
     * @param MyClassNullQuux|null $nullQuux
     * @return self
     */
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
        return $this->nullThud ?? null;
    }

    /**
     * @param string[]|null $nullThud
     * @return self
     * @param bool $validate
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

    /**
     * @return string|null
     */
    public function getOptFoo(): ?string
    {
        return $this->optFoo ?? null;
    }

    /**
     * @param string $optFoo
     * @return self
     * @param bool $validate
     */
    public function withOptFoo(string $optFoo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optFoo, self::$_schema['properties']['optFoo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optFoo = $optFoo;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptFoo(): self
    {
        $clone = clone $this;
        unset($clone->optFoo);

        return $clone;
    }

    /**
     * @return int|float|null
     */
    public function getOptBar(): int|float|null
    {
        return $this->optBar;
    }

    /**
     * @param int|float $optBar
     * @return self
     * @param bool $validate
     */
    public function withOptBar(int|float $optBar, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optBar, self::$_schema['properties']['optBar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optBar = $optBar;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptBar(): self
    {
        $clone = clone $this;
        unset($clone->optBar);

        return $clone;
    }

    /**
     * @return int|null
     */
    public function getOptBaz(): ?int
    {
        return $this->optBaz ?? null;
    }

    /**
     * @param int $optBaz
     * @return self
     * @param bool $validate
     */
    public function withOptBaz(int $optBaz, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optBaz, self::$_schema['properties']['optBaz']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optBaz = $optBaz;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptBaz(): self
    {
        $clone = clone $this;
        unset($clone->optBaz);

        return $clone;
    }

    /**
     * @return bool|null
     */
    public function getOptQux(): ?bool
    {
        return $this->optQux ?? null;
    }

    /**
     * @param bool $optQux
     * @return self
     * @param bool $validate
     */
    public function withOptQux(bool $optQux, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optQux, self::$_schema['properties']['optQux']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optQux = $optQux;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptQux(): self
    {
        $clone = clone $this;
        unset($clone->optQux);

        return $clone;
    }

    /**
     * @return MyClassOptQuux|null
     */
    public function getOptQuux(): ?MyClassOptQuux
    {
        return $this->optQuux ?? null;
    }

    /**
     * @param MyClassOptQuux $optQuux
     * @return self
     */
    public function withOptQuux(MyClassOptQuux $optQuux): self
    {
        $clone = clone $this;
        $clone->optQuux = $optQuux;

        return $clone;
    }

    /**
     * @return self
     */
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
        return $this->optThud ?? null;
    }

    /**
     * @param string[] $optThud
     * @return self
     * @param bool $validate
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

    /**
     * @return self
     */
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
     * @return self
     * @param bool $validate
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

    /**
     * @return self
     */
    public function withoutOptGrox(): self
    {
        $clone = clone $this;
        unset($clone->optGrox);
        unset($clone->_providedOptionals['optGrox']);

        return $clone;
    }

    /**
     * @return string|null
     */
    public function getOptNullFoo(): ?string
    {
        return $this->optNullFoo ?? null;
    }

    /**
     * @param string|null $optNullFoo
     * @return self
     * @param bool $validate
     */
    public function withOptNullFoo(?string $optNullFoo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optNullFoo, self::$_schema['properties']['optNullFoo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optNullFoo = $optNullFoo;
        $clone->_providedOptionals['optNullFoo'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptNullFoo(): self
    {
        $clone = clone $this;
        unset($clone->optNullFoo);
        unset($clone->_providedOptionals['optNullFoo']);

        return $clone;
    }

    /**
     * @return int|float|null
     */
    public function getOptNullBar(): int|float|null
    {
        return $this->optNullBar;
    }

    /**
     * @param int|float|null $optNullBar
     * @return self
     * @param bool $validate
     */
    public function withOptNullBar(int|float|null $optNullBar, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optNullBar, self::$_schema['properties']['optNullBar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optNullBar = $optNullBar;
        $clone->_providedOptionals['optNullBar'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptNullBar(): self
    {
        $clone = clone $this;
        unset($clone->optNullBar);
        unset($clone->_providedOptionals['optNullBar']);

        return $clone;
    }

    /**
     * @return int|null
     */
    public function getOptNullBaz(): ?int
    {
        return $this->optNullBaz ?? null;
    }

    /**
     * @param int|null $optNullBaz
     * @return self
     * @param bool $validate
     */
    public function withOptNullBaz(?int $optNullBaz, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optNullBaz, self::$_schema['properties']['optNullBaz']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optNullBaz = $optNullBaz;
        $clone->_providedOptionals['optNullBaz'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptNullBaz(): self
    {
        $clone = clone $this;
        unset($clone->optNullBaz);
        unset($clone->_providedOptionals['optNullBaz']);

        return $clone;
    }

    /**
     * @return bool|null
     */
    public function getOptNullQux(): ?bool
    {
        return $this->optNullQux ?? null;
    }

    /**
     * @param bool|null $optNullQux
     * @return self
     * @param bool $validate
     */
    public function withOptNullQux(?bool $optNullQux, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optNullQux, self::$_schema['properties']['optNullQux']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optNullQux = $optNullQux;
        $clone->_providedOptionals['optNullQux'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptNullQux(): self
    {
        $clone = clone $this;
        unset($clone->optNullQux);
        unset($clone->_providedOptionals['optNullQux']);

        return $clone;
    }

    /**
     * @return MyClassOptNullQuux|null
     */
    public function getOptNullQuux(): ?MyClassOptNullQuux
    {
        return $this->optNullQuux ?? null;
    }

    /**
     * @param MyClassOptNullQuux|null $optNullQuux
     * @return self
     */
    public function withOptNullQuux(?MyClassOptNullQuux $optNullQuux): self
    {
        $clone = clone $this;
        $clone->optNullQuux = $optNullQuux;
        $clone->_providedOptionals['optNullQuux'] = true;

        return $clone;
    }

    /**
     * @return self
     */
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
        return $this->optNullThud ?? null;
    }

    /**
     * @param string[]|null $optNullThud
     * @return self
     * @param bool $validate
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

    /**
     * @return self
     */
    public function withoutOptNullThud(): self
    {
        $clone = clone $this;
        unset($clone->optNullThud);
        unset($clone->_providedOptionals['optNullThud']);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
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
        $optGrox = property_exists($input, 'optGrox') ? ($input->{'optGrox'} !== null ? $input->{'optGrox'} : null) : null;
        if (property_exists($input, 'optGrox')) {
            $__providedOptionals['optGrox'] = true;
        }
        $optNullFoo = property_exists($input, 'optNullFoo') ? ($input->{'optNullFoo'} !== null ? $input->{'optNullFoo'} : null) : null;
        if (property_exists($input, 'optNullFoo')) {
            $__providedOptionals['optNullFoo'] = true;
        }
        $optNullBar = property_exists($input, 'optNullBar') ? ($input->{'optNullBar'} !== null ? $input->{'optNullBar'} : null) : null;
        if (property_exists($input, 'optNullBar')) {
            $__providedOptionals['optNullBar'] = true;
        }
        $optNullBaz = property_exists($input, 'optNullBaz') ? ($input->{'optNullBaz'} !== null ? $input->{'optNullBaz'} : null) : null;
        if (property_exists($input, 'optNullBaz')) {
            $__providedOptionals['optNullBaz'] = true;
        }
        $optNullQux = property_exists($input, 'optNullQux') ? ($input->{'optNullQux'} !== null ? $input->{'optNullQux'} : null) : null;
        if (property_exists($input, 'optNullQux')) {
            $__providedOptionals['optNullQux'] = true;
        }
        $optNullQuux = property_exists($input, 'optNullQuux') ? ($input->{'optNullQuux'} !== null ? MyClassOptNullQuux::fromInput($input->{'optNullQuux'}, $validate) : null) : null;
        if (property_exists($input, 'optNullQuux')) {
            $__providedOptionals['optNullQuux'] = true;
        }
        $optNullThud = property_exists($input, 'optNullThud') ? ($input->{'optNullThud'} !== null ? $input->{'optNullThud'} : null) : null;
        if (property_exists($input, 'optNullThud')) {
            $__providedOptionals['optNullThud'] = true;
        }

        $obj = new self($foo, $bar, $baz, $qux, $quux, $thud, $grox, $nullFoo, $nullBar, $nullBaz, $nullQux, $nullQuux, $nullThud);
        $obj->optFoo = $optFoo;
        $obj->optBar = $optBar;
        $obj->optBaz = $optBaz;
        $obj->optQux = $optQux;
        $obj->optQuux = $optQuux;
        $obj->optThud = $optThud;
        $obj->optGrox = $optGrox;
        $obj->optNullFoo = $optNullFoo;
        $obj->optNullBar = $optNullBar;
        $obj->optNullBaz = $optNullBaz;
        $obj->optNullQux = $optNullQux;
        $obj->optNullQuux = $optNullQuux;
        $obj->optNullThud = $optNullThud;
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
        $output['quux'] = ($this->quux)->toArray();
        $output['thud'] = $this->thud;
        $output['grox'] = $this->grox;
        $output['nullFoo'] = $this->nullFoo;
        $output['nullBar'] = $this->nullBar;
        $output['nullBaz'] = $this->nullBaz;
        $output['nullQux'] = $this->nullQux;
        $output['nullQuux'] = ($this->nullQuux)->toArray();
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
            $output['optQuux'] = ($this->optQuux)->toArray();
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
            $output['optNullQuux'] = ($this->optNullQuux !== null) ? (($this->optNullQuux !== null) ? (($this->optNullQuux)->toArray()) : null) : null;
        }
        if (isset($this->optNullThud) || array_key_exists('optNullThud', $this->_providedOptionals)) {
            $output['optNullThud'] = ($this->optNullThud !== null) ? (($this->optNullThud !== null) ? ($this->optNullThud) : null) : null;
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
        $output->{'quux'} = ($this->quux)->toStdClass();
        $output->{'thud'} = $this->thud;
        $output->{'grox'} = $this->grox;
        $output->{'nullFoo'} = $this->nullFoo;
        $output->{'nullBar'} = $this->nullBar;
        $output->{'nullBaz'} = $this->nullBaz;
        $output->{'nullQux'} = $this->nullQux;
        $output->{'nullQuux'} = ($this->nullQuux)->toStdClass();
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
            $output->{'optQuux'} = ($this->optQuux)->toStdClass();
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
            $output->{'optNullQuux'} = ($this->optNullQuux !== null) ? (($this->optNullQuux !== null) ? (($this->optNullQuux)->toStdClass()) : null) : null;
        }
        if (isset($this->optNullThud) || array_key_exists('optNullThud', $this->_providedOptionals)) {
            $output->{'optNullThud'} = ($this->optNullThud !== null) ? (($this->optNullThud !== null) ? ($this->optNullThud) : null) : null;
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
