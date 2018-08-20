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
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\Exception\UnexpectedTokenException;
use Railt\Parser\Exception\UnrecognizedTokenException;
use Railt\Reflection\Contracts\Document as DocumentInterface;
use Railt\Reflection\Contracts\Reflection as ReflectionInterface;
use Railt\Reflection\Reflection;
use Railt\SDL\Compiler\Builder;
use Railt\SDL\Compiler\Dictionary;
use Railt\SDL\Compiler\Parser;
use Railt\SDL\Exception\CompilerException;
use Railt\SDL\Exception\InternalException;
use Railt\SDL\Exception\SyntaxException;

/**
 * Class Compiler
 */
class Compiler implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var ReflectionInterface
     */
    private $reflection;

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var array|\Railt\Reflection\Contracts\Document[]
     */
    private $documents = [];

    /**
     * Compiler constructor.
     * @param LoggerInterface|null $logger
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->parser = new Parser();
        $this->builder = new Builder();
        $this->dictionary = new Dictionary($this);
        $this->reflection = new Reflection($this->dictionary);

        if ($logger) {
            $this->setLogger($logger);
        }
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;

        $this->builder->setLogger($logger);
        $this->dictionary->setLogger($logger);
    }

    /**
     * @param \Closure $then
     * @return Compiler
     */
    public function autoload(\Closure $then): Compiler
    {
        $this->dictionary->onTypeNotFound($then);

        return $this;
    }

    /**
     * @param Readable $file
     * @return DocumentInterface
     * @throws CompilerException
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function compile(Readable $file): DocumentInterface
    {
        try {
            return $this->memoize($file, function (Readable $file): DocumentInterface {
                return $this->builder->run($this->reflection, $file, $this->parse($file));
            });
        } catch (CompilerException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $error = new InternalException($e->getMessage(), $e->getCode());
            $error->throwsIn(File::fromPathname($e->getFile()), $e->getLine(), 0);

            throw $error;
        }
    }

    /**
     * @param Readable $file
     * @param \Closure $otherwise
     * @return DocumentInterface
     */
    private function memoize(Readable $file, \Closure $otherwise): DocumentInterface
    {
        if (isset($this->documents[$file->getHash()])) {
            //
            // Log memoized document selection
            //
            if ($this->logger) {
                $this->logger->debug(\sprintf('Avoid duplication compilation of %s', $file->getPathname()));
            }

            return $this->documents[$file->getHash()];
        }

        return $this->documents[$file->getHash()] = $otherwise($file);
    }

    /**
     * @param Readable $file
     * @return RuleInterface
     * @throws CompilerException
     */
    private function parse(Readable $file): RuleInterface
    {
        try {
            return $this->parser->parse($file);
        } catch (UnexpectedTokenException | UnrecognizedTokenException $e) {
            $error = new SyntaxException($e->getMessage(), $e->getCode());
            $error->throwsIn($file, $e->getLine(), $e->getColumn());

            throw $error;
        }
    }
}
