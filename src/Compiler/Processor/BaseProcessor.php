<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Processor;

use Railt\Lexer\Exception\LexerException;
use Railt\Parser\Exception\ParserException;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Dictionary;
use Railt\Reflection\Exception\ReflectionException;
use Railt\Reflection\Exception\TypeNotFoundException as TypeNotFoundReflectionException;
use Railt\SDL\Compiler\CallStack;
use Railt\SDL\Compiler\Pipeline;
use Railt\SDL\Exception\CompilerException;
use Railt\SDL\Exception\SemanticException;
use Railt\SDL\Exception\SyntaxException;
use Railt\SDL\Exception\TypeNotFoundException;

/**
 * Class BaseProcessor
 */
abstract class BaseProcessor implements Processable
{
    /**
     * @var int
     */
    public const PRIORITY_DEFINITION = 0x01;

    /**
     * @var int
     */
    public const PRIORITY_EXTENSION = 0x02;

    /**
     * @var int
     */
    public const PRIORITY_INVOCATION = 0x03;

    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * BaseProcessor constructor.
     * @param Pipeline $pipeline
     * @param CallStack $stack
     * @param Dictionary $dictionary
     */
    public function __construct(Pipeline $pipeline, CallStack $stack, Dictionary $dictionary)
    {
        $this->stack      = $stack;
        $this->pipeline   = $pipeline;
        $this->dictionary = $dictionary;
    }

    /**
     * @param string $type
     * @param Definition|null $from
     * @return TypeDefinition
     * @throws TypeNotFoundReflectionException
     */
    protected function get(string $type, Definition $from = null): TypeDefinition
    {
        return $this->dictionary->get($type, $from);
    }

    /**
     * @param Definition $definition
     * @param \Closure $then
     * @return mixed
     * @throws CompilerException
     */
    protected function transaction(Definition $definition, \Closure $then)
    {
        $this->stack->push($definition);

        $result = $this->wrap($definition, $then);

        $this->stack->pop();

        return $result;
    }

    /**
     * @param \Closure $then
     * @param Definition $def
     * @return mixed
     * @throws CompilerException
     */
    private function wrap(Definition $def, \Closure $then)
    {
        try {
            return $then($def);
        } catch (TypeNotFoundReflectionException $e) {
            throw $this->error(new TypeNotFoundException($e->getMessage(), $e->getCode(), $e), $def);
        } catch (ReflectionException $e) {
            throw $this->error(new SemanticException($e->getMessage(), $e->getCode(), $e), $def);
        } catch (LexerException $e) {
            throw $this->error(new SyntaxException($e->getMessage(), $e->getCode(), $e), $def);
        } catch (ParserException $e) {
            throw $this->error(new SyntaxException($e->getMessage(), $e->getCode(), $e), $def);
        } catch (CompilerException $e) {
            throw $e->using($this->stack);
        } catch (\Throwable $e) {
            throw $this->error(new CompilerException($e->getMessage(), $e->getCode(), $e), $def);
        }
    }

    /**
     * @param CompilerException $error
     * @param Definition $in
     * @return CompilerException
     */
    private function error(CompilerException $error, Definition $in): CompilerException
    {
        return $error->using($this->stack)->in($in);
    }

    /**
     * @param \Closure $then
     * @return Processable|$this
     */
    protected function immediately(\Closure $then): Processable
    {
        $this->pipeline->push(self::PRIORITY_DEFINITION, $then);

        return $this;
    }

    /**
     * @param \Closure $then
     * @return Processable|$this
     */
    protected function deferred(\Closure $then): Processable
    {
        $this->pipeline->push(self::PRIORITY_EXTENSION, $then);

        return $this;
    }

    /**
     * @param \Closure $then
     * @return Processable|$this
     */
    protected function future(\Closure $then): Processable
    {
        $this->pipeline->push(self::PRIORITY_INVOCATION, $then);

        return $this;
    }
}
