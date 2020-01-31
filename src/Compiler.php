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
use Phplrt\Source\Exception\NotFoundException;
use Phplrt\Source\Exception\NotReadableException;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Railt\SDL\Backend\Context;
use Railt\SDL\Backend\HashTable;
use Railt\SDL\Backend\HashTable\ValueFactory;
use Railt\SDL\Backend\HashTableInterface;
use Railt\SDL\Backend\Linker\LinkerFacadeTrait;
use Railt\SDL\Backend\Linker\LinkerInterface;
use Railt\SDL\Backend\Linker\Registry;
use Railt\SDL\Frontend\Generator;
use Railt\SDL\Spec\Railt;
use Railt\SDL\Spec\SpecificationInterface;

/**
 * Class Compiler
 */
final class Compiler implements CompilerInterface
{
    use LinkerFacadeTrait;
    use LoggerAwareTrait;

    /**
     * @var bool
     */
    private bool $booted = false;

    /**
     * @var ParserInterface
     */
    private ParserInterface $frontend;

    /**
     * @var Context
     */
    private Context $context;

    /**
     * @var SpecificationInterface
     */
    private SpecificationInterface $spec;

    /**
     * @var LinkerInterface
     */
    private LinkerInterface $linker;

    /**
     * @var HashTable
     */
    private HashTable $vars;

    /**
     * @var ValueFactory
     */
    private ValueFactory $values;

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

        $this->context = new Context();
        $this->spec = $spec ?? new Railt();
        $this->frontend = new Frontend($cache);
        $this->linker = new Registry();
        $this->values = new ValueFactory();
        $this->vars = new HashTable($this->values);
    }

    /**
     * @return LinkerInterface
     */
    public function getLinker(): LinkerInterface
    {
        return $this->linker;
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
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
     * @throws NotFoundException
     * @throws NotReadableException
     */
    private function bootIfNotBooted(): void
    {
        if ($this->booted === false) {
            $this->booted = true;

            $this->spec->load($this);
        }
    }

    /**
     * @param iterable $ast
     * @param Context $ctx
     * @param array $variables
     * @return SchemaInterface
     * @throws \Throwable
     */
    private function backend(iterable $ast, Context $ctx, array $variables = []): SchemaInterface
    {
        $executor = new Backend($this->spec, $ctx, $this->linker);

        return $executor->run($ast, $this->getHashTable($variables));
    }

    /**
     * @param array $vars
     * @return HashTableInterface
     */
    private function getHashTable(array $vars = []): HashTableInterface
    {
        return new HashTable($this->values, $vars, $this->vars);
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
     * @throws InvalidArgumentException
     */
    public function compile($source, array $variables = []): SchemaInterface
    {
        $this->bootIfNotBooted();

        return $this->backend($this->frontend($source), clone $this->context, $variables);
    }

    /**
     * @return void
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    public function rebuild(): void
    {
        (new Generator())->generateAndSave();
    }
}
