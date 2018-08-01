<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Definition;

use Railt\Parser\Ast\Delegate;
use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\Rule;
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\Environment;
use Railt\Reflection\AbstractTypeDefinition;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\CallStack;
use Railt\SDL\Compiler\Pipeline;
use Railt\SDL\Compiler\Value;
use Railt\SDL\Compiler\Value\ValueInterface;
use Railt\SDL\Exception\CompilerException;

/**
 * Class DefinitionDelegate
 */
abstract class DefinitionDelegate extends Rule implements Delegate
{
    /**
     * @var TypeDefinition|AbstractTypeDefinition
     */
    protected $definition;

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var Document|\Railt\Reflection\Document
     */
    private $document;

    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * @param Environment $env
     */
    public function boot(Environment $env): void
    {
        /** @var \Railt\Reflection\Document $document */
        $this->document = $env->get(Document::class);
        $this->stack    = $env->get(CallStack::class);
        $this->pipeline = $env->get(Pipeline::class);

        $this->definition = $this->create($this->document);
        $this->definition->withOffset($this->getOffset());
    }

    /**
     * @param Document $document
     * @return Definition
     */
    abstract protected function create(Document $document): Definition;

    /**
     * @param int $priority
     * @param callable $then
     */
    protected function future(int $priority, callable $then): void
    {
        $this->pipeline->push($priority, function () use ($then) {
            $this->transaction($this->definition, function () use ($then) {
                $then();
            });
        });
    }

    /**
     * @param Definition $definition
     * @param \Closure $then
     * @return mixed
     */
    protected function transaction(Definition $definition, \Closure $then)
    {
        return $this->stack->transaction($definition, $then);
    }

    /**
     * @return CallStack
     */
    protected function getCallStack(): CallStack
    {
        return $this->stack;
    }

    /**
     * @param NodeInterface|null $node
     * @return null|string
     */
    protected function getTypeName(NodeInterface $node = null): ?string
    {
        /** @var RuleInterface|null $name */
        $name = ($node ?? $this)->first('TypeName', 1);

        return $name ? $name->getChild(0)->getValue() : null;
    }

    /**
     * @param NodeInterface $rule
     * @return ValueInterface
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    protected function value(NodeInterface $rule): ValueInterface
    {
        try {
            return Value::parse($rule, $this->definition->getFile());
        } catch (CompilerException $e) {
            throw $this->error($e)->throwsIn($this->definition->getFile(), $rule->getOffset());
        }
    }

    /**
     * @param CompilerException $exception
     * @return CompilerException
     */
    protected function error(CompilerException $exception): CompilerException
    {
        return $exception->in($this->definition)->using($this->stack);
    }

    /**
     * @param Definition $def
     */
    protected function push(Definition $def): void
    {
        $this->stack->push($def);
    }

    /**
     * @param int $count
     * @return void
     */
    protected function pop(int $count = 1): void
    {
        $count = \max(1, $count);

        for ($i = 0; $i <= $count; ++$i) {
            $this->stack->pop();
        }
    }
}
