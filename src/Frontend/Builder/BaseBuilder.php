<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder;

use Railt\Io\Readable;
use Railt\SDL\Exception\NotFoundException;
use Railt\SDL\Frontend\Builder;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Deferred\DeferredInterface;
use Railt\SDL\Frontend\Deferred\Storage;
use Railt\SDL\Frontend\Invocation\InvocationPrimitive;
use Railt\SDL\IR\SymbolTable\PrimitiveInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class BaseBuilder
 */
abstract class BaseBuilder implements BuilderInterface
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var Storage
     */
    private $store;

    /**
     * BaseBuilder constructor.
     * @param Builder $builder
     * @param Storage $store
     */
    public function __construct(Builder $builder, Storage $store)
    {
        $this->builder = $builder;
        $this->store   = $store;
    }

    /**
     * @param Readable $file
     * @return iterable
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\SDL\Exception\SyntaxException
     */
    protected function loadFile(Readable $file)
    {
        return $this->builder->buildFile($file);
    }

    /**
     * @param InvocationPrimitive|PrimitiveInterface $invocation
     * @param ContextInterface $context
     * @return mixed
     * @throws NotFoundException
     */
    protected function invoke(InvocationPrimitive $invocation, ContextInterface $context)
    {
        $deferred = $this->loadType($invocation->getName(), $context);

        if ($deferred === null) {
            $error = 'Type %s not found or could not be loaded';
            throw new NotFoundException(\sprintf($error, $invocation->getName()));
        }

        return $deferred->invoke($invocation, $context);
    }

    /**
     * @param TypeNameInterface $name
     * @param ContextInterface|null $ctx
     * @return DeferredInterface|null
     */
    protected function loadType(TypeNameInterface $name, ContextInterface $ctx = null): ?DeferredInterface
    {
        if ($ctx && $result = $this->store->first($name->in($ctx->getName()))) {
            return $result;
        }

        return $this->store->first($name);
    }
}
