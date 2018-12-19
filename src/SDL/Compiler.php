<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\Io\Exception\ExternalFileException;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Document as DocumentInterface;
use Railt\Reflection\Contracts\Reflection as ReflectionInterface;
use Railt\Reflection\Dictionary\CallbackDictionary;
use Railt\Reflection\Document;
use Railt\Reflection\Reflection;
use Railt\SDL\Compiler\Backend;
use Railt\SDL\Compiler\Frontend;
use Railt\SDL\Compiler\Process;
use Railt\SDL\Exception\InternalErrorException;
use Railt\SDL\Exception\SyntaxException;
use Railt\SDL\Exception\TypeException;
use Railt\SDL\TypeLoader\TypeLoaderInterface;

/**
 * Class Compiler
 */
class Compiler implements CompilerInterface
{
    /**
     * @var ReflectionInterface
     */
    private $reflection;

    /**
     * @var Frontend
     */
    private $frontend;

    /**
     * @var Backend
     */
    private $backend;

    /**
     * @var Process
     */
    private $process;

    /**
     * @var array|TypeLoaderInterface[]
     */
    private $loaders = [];

    /**
     * Compiler constructor.
     */
    public function __construct()
    {
        $this->process = new Process();
        $this->frontend = new Frontend();
        $this->backend = new Backend($this->process);
        $this->reflection = new Reflection(new CallbackDictionary($this->typeLoader()));
    }

    /**
     * @param TypeLoaderInterface $loader
     */
    public function autoload(TypeLoaderInterface $loader): void
    {
        $this->loaders[] = $loader;
    }

    /**
     * @return \Closure
     */
    private function typeLoader(): \Closure
    {
        return function (string $type, Definition $from = null): ?DocumentInterface {
            foreach ($this->loaders as $loader) {
                if ($file = $loader->load($type, $from)) {
                    return $this->compile($file);
                }
            }

            return null;
        };
    }

    /**
     * @param Readable $schema
     * @return DocumentInterface
     * @throws ExternalFileException
     * @throws InternalErrorException
     */
    public function compile(Readable $schema): DocumentInterface
    {
        try {
            $document = $this->runCompiler($schema);

            $this->process->run();

            return $document;
        } catch (ExternalFileException $e) {
            $class = \get_class($e);

            /** @var ExternalFileException $exception */
            $exception = new $class($e->getMessage());
            $exception->throwsIn($schema, $e->getLine(), $e->getColumn());

            throw $exception;
        }
    }

    /**
     * @param Readable $schema
     * @return DocumentInterface
     * @throws InternalErrorException
     * @throws SyntaxException
     * @throws TypeException
     */
    private function runCompiler(Readable $schema): DocumentInterface
    {
        return $this->run(clone $this->reflection, $schema);
    }

    /**
     * @param ReflectionInterface $reflection
     * @param Readable $schema
     * @return DocumentInterface
     * @throws InternalErrorException
     * @throws SyntaxException
     * @throws TypeException
     */
    private function run(ReflectionInterface $reflection, Readable $schema): DocumentInterface
    {
        // TODO
        return $this->document($reflection, $schema);

        return $this->backend->each(
            $this->backend->context($this->document($reflection, $schema)),
            $this->frontend->exec($schema)
        );
    }

    /**
     * @param ReflectionInterface $reflection
     * @param Readable $schema
     * @return DocumentInterface
     */
    private function document(ReflectionInterface $reflection, Readable $schema): DocumentInterface
    {
        return new Document($reflection, $schema);
    }

    /**
     * @param Readable $schema
     * @return ReflectionInterface
     * @throws InternalErrorException
     * @throws SyntaxException
     * @throws TypeException
     */
    public function preload(Readable $schema): ReflectionInterface
    {
        $this->run($this->reflection, $schema);

        return $this->reflection;
    }
}
