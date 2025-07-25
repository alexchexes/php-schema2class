<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\DeclareStatement;
use Laminas\Code\Generator\ClassGenerator as LaminasClassGenerator;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\FileGenerator;

/**
 * Handles creation of the Laminas class representation and writing it using a writer.
 */
class ClassFileWriter
{
    public function __construct(private WriterInterface $writer)
    {
    }

    /**
     * @param array $schema Validation schema
     * @param array $properties PropertyGenerator[]
     * @param array $methods MethodGenerator[]
     */
    public function write(GeneratorRequest $req, array $schema, array $properties, array $methods): void
    {
        $cls = new LaminasClassGenerator(
            $req->getTargetClass(),
            $req->getTargetNamespace(),
            null,
            null,
            [],
            $properties,
            $methods,
            null,
        );

        if (isset($schema['description']) && is_string($schema['description']) && $schema['description'] !== '') {
            $doc = new DocBlockGenerator($schema['description']);
            $doc->setWordWrap(false);
            $cls->setDocBlock($doc);
        }

        $req->onClassCreated($cls);

        $filename = $req->getTargetDirectory() . '/' . $req->getTargetClass() . '.php';

        $file = new FileGenerator();
        $file->setClasses([$cls]);

        $req->onFileCreated($filename, $file);

        if ($req->isAtLeastPHP('7.0') && !$req->getOptions()->getDisableStrictTypes()) {
            $file->setDeclares([DeclareStatement::strictTypes(1)]);
        }

        $content = $file->generate();

        $content = preg_replace('/: \\\self/', ': self', $content);
        $content = preg_replace('/\\\\' . preg_quote($req->getTargetNamespace(), '/') . '\\\\/', '', $content);
        $ownClasses = $req->getGeneratedClassNames();
        if ($ownClasses) {
            $escapedOwnClasses = array_map(fn ($n) => preg_quote($n, '/'), $ownClasses);
            $pattern = '/\\\\(' . join('|', $escapedOwnClasses) . ')(?=\s|[,;)|]|$)/';
            $content = preg_replace($pattern, '$1', $content);
        }

        $this->writer->writeFile($filename, $content);
    }
}
