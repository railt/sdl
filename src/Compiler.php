<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\Environment;
use Railt\Parser\Exception\UnexpectedTokenException;
use Railt\Parser\Exception\UnrecognizedTokenException;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Dictionary;
use Railt\Reflection\Contracts\Document as DocumentInterface;
use Railt\Reflection\Contracts\Reflection as ReflectionInterface;
use Railt\Reflection\Dictionary\CallbackDictionary;
use Railt\Reflection\Document;
use Railt\Reflection\Reflection;
use Railt\SDL\Compiler\Compilable;
use Railt\SDL\Exception\CompilerException;
use Railt\SDL\Exception\SyntaxException;

/**
 * Class Compiler
 */
class Compiler
{
    /**
     * @var ReflectionInterface
     */
    private $reflection;

    /**
     * @var Dictionary|CallbackDictionary
     */
    private $dictionary;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Environment
     */
    private $env;

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var array|Document[]
     */
    private $documents = [];

    /**
     * Compiler constructor.
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function __construct()
    {
        $this->parser     = new Parser();
        $this->stack      = new CallStack();
        $this->dictionary = new CallbackDictionary();
        $this->reflection = new Reflection($this->dictionary);

        $this->env = $this->parser->env();

        foreach ($this->reflection->getDocuments() as $document) {
            $this->memomize($document);
        }
    }

    /**
     * @param \Closure $then
     */
    public function autoload(\Closure $then): void
    {
        $this->dictionary->onTypeNotFound(function (string $type, ?Definition $from) use ($then): void {
            if (($file = $then($type, $from)) instanceof Readable) {
                $this->compile($file);
            }
        });
    }

    /**
     * @param Document $document
     * @param Readable $file
     * @return Compiler
     */
    private function load(Document $document, Readable $file): self
    {
        $this->env->share(Dictionary::class, $this->dictionary);
        $this->env->share(ReflectionInterface::class, $this->reflection);
        $this->env->share(CallStack::class, $this->stack);
        $this->env->share(DocumentInterface::class, $document);
        $this->env->share(Readable::class, $file);

        return $this;
    }

    /**
     * @param Document $document
     * @param \Closure|null $otherwise
     * @return Document
     */
    private function memomize(Document $document, \Closure $otherwise = null): Document
    {
        $key = $document->getFile()->getHash();

        if (! isset($this->documents[$key])) {
            $otherwise && $otherwise($document);
        }

        return $this->documents[$key] = $document;
    }

    /**
     * @param Readable $file
     * @return DocumentInterface
     */
    public function compile(Readable $file): DocumentInterface
    {
        return $this->memomize(new Document($this->reflection, $file), function (Document $document) use ($file): void {
            $ast = $this->parse($document, $file);

            foreach ($ast as $type) {
                if ($type instanceof Compilable) {
                    $type->compile();
                }
            }
        });
    }

    /**
     * @param Document $document
     * @param Readable $file
     * @return RuleInterface
     * @throws CompilerException
     */
    private function parse(Document $document, Readable $file): RuleInterface
    {
        try {
            return $this->load($document, $file)->parser->parse($file);
        } catch (UnexpectedTokenException | UnrecognizedTokenException $e) {
            $error = new SyntaxException($e->getMessage());
            $error->using($this->stack);
            $error->throwsIn($file, $e->getLine(), $e->getColumn());

            throw $error;
        }
    }
}
