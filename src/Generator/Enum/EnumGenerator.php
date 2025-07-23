<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Enum;

use PhpParser\Builder\Enum_ as EnumBuilder;
use PhpParser\BuilderFactory;
use PhpParser\Node\Stmt;
use PhpParser\PrettyPrinter\Standard;

/**
 * Simple wrapper around nikic/php-parser to build an enum and generate code.
 */
class EnumGenerator
{
    private EnumBuilder $builder;
    private BuilderFactory $factory;
    private Standard $printer;
    private string $namespace;

    /**
     * @param array<non-empty-string,string|int> $cases
     */
    public function __construct(string $enumName, string $type, array $cases)
    {
        $this->factory  = new BuilderFactory();
        $this->printer  = new Standard();
        $parts          = explode('\\', $enumName);
        $short          = array_pop($parts);
        $this->namespace = implode('\\', $parts);
        $this->builder  = $this->factory->enum($short)->setScalarType($type);
        foreach ($cases as $name => $value) {
            $this->builder->addStmt(
                $this->factory->enumCase($name)->setValue($value)
            );
        }
    }

    public function getBuilder(): EnumBuilder
    {
        return $this->builder;
    }

    /**
     * Generate PHP code for the enum.
     */
    public function generate(): string
    {
        $stmts   = [new Stmt\Declare_([
            new Stmt\DeclareDeclare(
                new \PhpParser\Node\Identifier('strict_types'),
                new \PhpParser\Node\Scalar\LNumber(1)
            ),
        ])];

        $enumNode = $this->builder->getNode();
        if ($this->namespace !== '') {
            $ns = $this->factory->namespace($this->namespace)->addStmt($enumNode)->getNode();
            $stmts[] = $ns;
        } else {
            $stmts[] = $enumNode;
        }

        $code = "<?php\n\n" . $this->printer->prettyPrint($stmts) . "\n";
        $code = preg_replace(
            '/declare \(strict_types=1\);\nnamespace/',
            "declare(strict_types=1);\n\nnamespace",
            $code,
        );
        $code = preg_replace('/enum ([^:]+) : /', 'enum $1: ', $code);
        $code = preg_replace('/(enum[^\n]+)\n\{/', '$1 {', $code);
        return $code;
    }
}
