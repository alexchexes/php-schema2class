<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\DeclareStatement;
use Laminas\Code\Generator\ClassGenerator as LaminasClassGenerator;
use Laminas\Code\Generator\FileGenerator;

/**
 * Handles creation of the Laminas class representation and writing it using a writer.
 */
class ClassFileWriter
{
    public function __construct(
        private GeneratorRequest $request,
        private WriterInterface $writer,
    ) {}

    public function write(LaminasClassGenerator $classGenerator): void
    {
        $filename = $this->request->getTargetDirectory() . '/' . $this->request->getTargetClass() . '.php';

        $fileGenerator = new FileGenerator();
        $fileGenerator->setClasses([$classGenerator]);

        // Invoke a hook
        $this->request->onFileCreated($filename, $fileGenerator);

        if ($this->request->isAtLeastPHP('7.0') && !$this->request->getOptions()->getDisableStrictTypes()) {
            $fileGenerator->setDeclares([DeclareStatement::strictTypes(1)]);
        }

        $content = $fileGenerator->generate();
        $content = $this->postProcess($content);

        $this->writer->writeFile($filename, $content);
    }

    private function postProcess(string $content)
    {
        $content = preg_replace('/: \\\self/', ': self', $content);
        $content = preg_replace('/\\\\' . preg_quote($this->request->getTargetNamespace(), '/') . '\\\\/', '', $content);
        $ownClasses = $this->request->getGeneratedClassNames();

        if ($ownClasses) {
            $escapedOwnClasses = array_map(fn ($n) => preg_quote($n, '/'), $ownClasses);
            $pattern = '/\\\\(' . join('|', $escapedOwnClasses) . ')(?=\s|[,;)|]|$)/';
            $content = preg_replace($pattern, '$1', $content);
        }

        return $content;
    }
}
