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
use Railt\SDL\Compiler\Pipeline;
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
     * @var Pipeline
     */
    private $pipeline;

    /**
     * Compiler constructor.
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function __construct()
    {
        $this->parser     = new Parser();
        $this->stack      = new CallStack();
        $this->pipeline   = new Pipeline();
        $this->dictionary = new CallbackDictionary();
        $this->reflection = new Reflection($this->dictionary);

        $this->env = $this->parser->env();
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
        $this->env->share(Pipeline::class, $this->pipeline);

        return $this;
    }

    /**
     * @param Readable $file
     * @return DocumentInterface
     * @throws CompilerException
     */
    public function compile(Readable $file): DocumentInterface
    {
        $document = new Document($this->reflection, $file);

        $this->parse($document, $file);

        foreach ($this->pipeline as $invocation) {
            $invocation();
        }

        return $document;
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
