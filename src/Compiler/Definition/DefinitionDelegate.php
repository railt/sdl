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
use Railt\Parser\Ast\Rule;
use Railt\Parser\Environment;
use Railt\Reflection\AbstractDefinition;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Dictionary;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\CallStack;
use Railt\SDL\Compiler\Compilable;
use Railt\SDL\Exception\CompilerException;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class DefinitionDelegate
 */
abstract class DefinitionDelegate extends Rule implements Delegate, Compilable
{
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
    private function verifyDuplication(TypeDefinition $type, Dictionary $dict): void
    {
        if ($dict->has($type->getName())) {
            $prev  = $dict->get($type->getName(), $type);
            $error = 'Could not redeclare type %s as %s';
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
}
