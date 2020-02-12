<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL;

use GraphQL\Contracts\TypeSystem\SchemaInterface;
use Phplrt\Contracts\Parser\ParserInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Railt\SDL\Backend\ExecutionContext;
use Railt\SDL\Backend\HashTable;
use Railt\SDL\Backend\HashTableInterface;
use Railt\SDL\Compiler\ContextFacadeTrait;
use Railt\SDL\Compiler\DevelopmentModeFacadeTrait;
use Railt\SDL\Compiler\HashTableFacadeTrait;
use Railt\SDL\Compiler\LinkerFacadeTrait;
use Railt\SDL\Compiler\SpecificationFacadeTrait;
use Railt\SDL\Spec\SpecificationInterface;

/**
 * Class Compiler
 */
final class Compiler implements CompilerInterface
{
    use LinkerFacadeTrait;
    use ContextFacadeTrait;
    use HashTableFacadeTrait;
    use SpecificationFacadeTrait;
    use DevelopmentModeFacadeTrait;

    /**
     * @var bool
     */
    private bool $booted = false;

    /**
     * @var ParserInterface
     */
    private ParserInterface $frontend;

    /**
     * Compiler constructor.
     *
     * @param SpecificationInterface $spec
     * @param CacheInterface|null $cache
     * @param LoggerInterface|null $logger
     * @throws \Throwable
     */
    public function __construct(
        SpecificationInterface $spec = null,
        CacheInterface $cache = null,
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger;
        $this->frontend = new Frontend($cache);

        $this->bootLinkerFacadeTrait();
        $this->bootContextFacadeTrait();
        $this->bootHashTableFacadeTrait();

        $this->setSpecification($spec);
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     * @throws InvalidArgumentException
     */
    public function preload($source, array $variables = []): self
    {
        $this->bootIfNotBooted();

        $this->backend($this->frontend($source), $this->context, $variables);

        return $this;
    }

    /**
     * @return void
     */
    private function bootIfNotBooted(): void
    {
        if ($this->booted === false) {
            $this->booted = true;

            $this->bootSpecificationFacadeTrait();
        }
    }

    /**
     * @param iterable $ast
     * @param ExecutionContext $ctx
     * @param array $variables
     * @return SchemaInterface
     * @throws \Throwable
     */
    private function backend(iterable $ast, ExecutionContext $ctx, array $variables = []): SchemaInterface
    {
        $hash = $this->createVariablesContext($variables);

        $executor = new Backend($this, $ctx);

        return $executor->run($ast, $hash);
    }

    /**
     * @param array $variables
     * @return HashTableInterface
     */
    private function createVariablesContext(array $variables = []): HashTableInterface
    {
        return new HashTable($this->getValueFactory(), $variables, $this->getVariables());
    }

    /**
     * @param mixed $source
     * @return iterable
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    private function frontend($source): iterable
    {
        return $this->frontend->parse($source);
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    public function compile($source, array $variables = []): SchemaInterface
    {
        $this->bootIfNotBooted();

        try {
            return $this->backend($this->frontend($source), clone $this->context, $variables);
        } catch (InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }
}
