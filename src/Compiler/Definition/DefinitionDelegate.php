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
use Railt\Reflection\AbstractDefinition;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Dictionary;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\CallStack;
use Railt\SDL\Compiler\Compilable;
use Railt\SDL\Compiler\Definition\Common\DescriptionTrait;
use Railt\SDL\Compiler\Value;
use Railt\SDL\Compiler\Value\ValueInterface;
use Railt\SDL\Exception\CompilerException;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class DefinitionDelegate
 */
abstract class DefinitionDelegate extends Rule implements Delegate, Compilable
{
    use DescriptionTrait;

    /**
     * @var TypeDefinition
     */
    private $definition;

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var Document|\Railt\Reflection\Document
     */
    private $document;

    /**
     * @param Environment $env
     */
    public function boot(Environment $env): void
    {
        /** @var \Railt\Reflection\Document $document */
        $this->document = $env->get(Document::class);
        $this->stack    = $env->get(CallStack::class);

        $this->definition = $this->bootDefinition($this->document);

        $this->stack->transaction($this->definition, function (AbstractDefinition $record): void {
            $record->withOffset($this->getOffset());

            if ($record instanceof TypeDefinition) {
                $this->verifyDuplication($record, $this->document->getDictionary());
                $this->withDescription($record, $this);

                $this->document->withDefinition($record);
            }

            $this->before($record);
        });
    }

    /**
     * @param Document $document
     * @return Definition
     */
    abstract protected function bootDefinition(Document $document): Definition;

    /**
     * @param TypeDefinition $type
     * @param Dictionary $dict
     * @throws CompilerException
     * @throws \Railt\Reflection\Exception\TypeNotFoundException
     */
    private function verifyDuplication(TypeDefinition $type, Dictionary $dict)
    {
        if ($dict->has($type->getName())) {
            $prev  = $dict->get($type->getName(), $type);
            $error = 'Could not redeclare type %s by %s';
            $error = \sprintf($error, $prev, $type);

            throw $this->error(new TypeConflictException($error));
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
     * @param Definition $definition
     */
    protected function before(Definition $definition): void
    {

    }

    /**
     * @return void
     */
    public function compile(): void
    {
        $this->after($this->definition);
    }

    /**
     * @param Definition $definition
     */
    protected function after(Definition $definition): void
    {

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
     * @param Definition $def
     */
    protected function push(Definition $def): void
    {
        $this->stack->push($def);
    }

    /**
     * @param Definition $definition
     * @param \Closure $then
     */
    protected function transaction(Definition $definition, \Closure $then): void
    {
        $this->stack->transaction($definition, $then);
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
