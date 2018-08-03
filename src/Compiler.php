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
use Railt\SDL\Compiler\Parser;
use Railt\SDL\Compiler\Pipeline;
use Railt\SDL\Compiler\Factory;
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
     * Compiler constructor.
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function __construct()
    {
        $this->dictionary = new CallbackDictionary();
        $this->reflection = new Reflection($this->dictionary);
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
     * @param Readable $file
     * @return DocumentInterface
     * @throws CompilerException
     */
    public function compile(Readable $file): DocumentInterface
    {
        $document = new Document($this->reflection, $file);

        $processor = new Factory($document, $this->parse($document, $file));

        return $processor->process();
    }

    /**
     * @param Document $document
     * @param Readable $file
     * @return RuleInterface
     * @throws CompilerException
     */
    private function parse(Document $document, Readable $file): RuleInterface
    {
        $parser = new Parser();
        $parser->env()->share(DocumentInterface::class, $document);

        try {
            return $parser->parse($file);
        } catch (UnexpectedTokenException | UnrecognizedTokenException $e) {
            $error = new SyntaxException($e->getMessage());
            $error->throwsIn($file, $e->getLine(), $e->getColumn());

            throw $error;
        }
    }
}
