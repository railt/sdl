<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Reflection\Contracts\Document as DocumentInterface;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Reflection;
use Railt\SDL\Compiler\Dictionary;
use Railt\SDL\Compiler\Store;
use Railt\SDL\Exception\CompilerException;
use Railt\SDL\Exception\InternalException;
use Railt\SDL\Frontend\Context\LocalContext;
use Railt\SDL\Frontend\Record\RecordInterface;

/**
 * Class Compiler
 */
class Compiler implements LoggerAwareInterface, CompilerInterface
{
    use LoggerAwareTrait;

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var Store
     */
    private $store;

    /**
     * @var Frontend
     */
    private $front;

    /**
     * @var Backend
     */
    private $back;

    /**
     * Compiler constructor.
     * @param LoggerInterface|null $logger
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->store = new Store();
        $this->front = new Frontend();
        $this->dictionary = new Dictionary($this);
        $this->back = new Backend($this->front, new Reflection($this->dictionary));

        if ($logger) {
            $this->setLogger($logger);
        }
    }

    /**
     * @param LoggerInterface $logger
     * @return Compiler
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        $this->front->setLogger($logger);
        $this->back->setLogger($logger);

        return $this;
    }

    /**
     * @param \Closure $then
     * @return CompilerInterface|$this
     */
    public function autoload(\Closure $then): CompilerInterface
    {
        $this->dictionary->onTypeNotFound($then);

        return $this;
    }

    /**
     * @param Readable $file
     * @return DocumentInterface
     */
    public function compile(Readable $file): DocumentInterface
    {
        return $this->store->memoize($file, function (Readable $file): DocumentInterface {
            return $this->generate($file, $this->ir($file));
        });
    }

    /**
     * @param Readable $readable
     * @param iterable $records
     * @return Document
     * @throws \Railt\Io\Exception\NotReadableException
     */
    private function generate(Readable $readable, iterable $records): Document
    {
        return $this->wrap(function () use ($readable, $records) {
            return $this->back->run($readable, $records);
        });
    }

    /**
     * @param Readable $readable
     * @return iterable|RecordInterface[]
     * @throws \Railt\Io\Exception\NotReadableException
     */
    private function ir(Readable $readable): iterable
    {
        return $this->wrap(function () use ($readable) {
            return $this->front->load($readable);
        });
    }

    /**
     * @param \Closure $runner
     * @return mixed
     * @throws CompilerException
     * @throws InternalException
     * @throws \Railt\Io\Exception\NotReadableException
     */
    private function wrap(\Closure $runner)
    {
        try {
            return $runner();
        } catch (CompilerException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $error = new InternalException($e->getMessage(), $e->getCode(), $e);
            $error->throwsIn(File::fromPathname($e->getFile()), $e->getLine(), 0);

            throw $error;
        }
    }
}
