<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\TestCase;
use Helmich\Schema2Class\Generator\Class\SchemaPropertyCollector;

class IdentifierResolverTest extends TestCase
{
    private function createRequest(array $schema, bool $preserve = false): GeneratorRequest
    {
        $opts = (new SpecificationOptions())->withPreservePropertyNames($preserve);
        return new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns', 'Cls', ''),
            $opts,
        );
    }

    public function testNamesStableRegardlessOfOrder(): void
    {
        $schema1 = ['properties' => [
            'foo-bar' => ['type' => 'string'],
            'foo bar' => ['type' => 'string'],
        ]];
        $schema2 = ['properties' => [
            'foo bar' => ['type' => 'string'],
            'foo-bar' => ['type' => 'string'],
        ]];

        $collector = new SchemaPropertyCollector();
        $req1 = $this->createRequest($schema1, preserve: true);
        $req2 = $this->createRequest($schema2, preserve: true);
        $props1 = $collector->collectPropertiesFromSchema($schema1, $req1);
        $props2 = $collector->collectPropertiesFromSchema($schema2, $req2);

        (new IdentifierResolver($req1))->resolve($props1);
        (new IdentifierResolver($req2))->resolve($props2);

        $names1 = [];
        foreach ($props1 as $p) {
            $names1[$p->key()] = [$p->name(), $p->methodName(), $p->varName()];
        }
        $names2 = [];
        foreach ($props2 as $p) {
            $names2[$p->key()] = [$p->name(), $p->methodName(), $p->varName()];
        }
        ksort($names1);
        ksort($names2);

        self::assertSame($names1, $names2);
    }

    public function testCaseVariantMethodNamesUnique(): void
    {
        $schema = ['properties' => [
            'bar' => ['type' => 'string'],
            'Bar' => ['type' => 'string'],
        ]];
        $collector = new SchemaPropertyCollector();
        $req = $this->createRequest($schema, preserve: true);
        $props = $collector->collectPropertiesFromSchema($schema, $req);
        (new IdentifierResolver($req))->resolve($props);

        $methods = [];
        foreach ($props as $p) {
            $methods[$p->key()] = $p->methodName();
        }

        self::assertNotEquals(
            strtolower($methods['bar']),
            strtolower($methods['Bar'])
        );
    }

    public function testVarNamesAvoidReserved(): void
    {
        $schema = ['properties' => [
            'input' => ['type' => 'string'],
            'GLOBALS' => ['type' => 'string'],
        ]];
        $collector = new SchemaPropertyCollector();
        $req = $this->createRequest($schema, preserve: true);
        $props = $collector->collectPropertiesFromSchema($schema, $req);
        (new IdentifierResolver($req))->resolve($props);

        $vars = [];
        foreach ($props as $p) {
            $vars[$p->key()] = $p->varName();
        }

        self::assertNotSame('input', $vars['input']);
        self::assertNotSame('GLOBALS', $vars['GLOBALS']);
    }
}
