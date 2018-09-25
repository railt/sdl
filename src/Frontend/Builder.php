<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Exception\InternalException;
use Railt\SDL\Frontend;
use Railt\SDL\Frontend\Builder\BuilderInterface;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\Frontend\Builder\ImportBuilder;
use Railt\SDL\Frontend\Builder\NamespaceBuilder;
use Railt\SDL\Frontend\Context\Context;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Record\RecordInterface;
use Railt\SDL\Frontend\Record\Store;
use Railt\SDL\Frontend\Type\TypeName;
use Railt\SDL\Frontend\Type\TypeNameInterface;

/**
 * Class Builder
 */
class Builder
{
    /**
     * @var string[]|BuilderInterface[]
     */
    private const DEFAULT_BUILDER_DEFINITIONS = [
        NamespaceBuilder::class,
        ImportBuilder::class,
        DefinitionBuilder::class,
    ];

    /**
     * @var array|BuilderInterface[]
     */
    private $builders = [];

    /**
     * @var Store
     */
    private $store;

    /**
     * @var Frontend
     */
    private $frontend;

    /**
     * Builder constructor.
     * @param Frontend $frontend
     */
    public function __construct(Frontend $frontend)
    {
        $this->frontend = $frontend;
        $this->store = new Store();
        $this->bootDefaults();
    }

    /**
     * @param Readable $readable
     * @return RecordInterface[]|\Traversable
     */
    public function load(Readable $readable): iterable
    {
        return $this->frontend->load($readable);
    }

    /**
     * @return void
     */
    private function bootDefaults(): void
    {
        foreach (self::DEFAULT_BUILDER_DEFINITIONS as $builder) {
            $this->builders[] = new $builder($this);
        }
    }

    /**
     * @param Readable $file
     * @param RuleInterface $ast
     * @return iterable|RecordInterface[]
     */
    public function build(Readable $file, RuleInterface $ast): iterable
    {
        $context = new Context($file);

        foreach ($ast->getChildren() as $child) {
            if ($result = $this->reduce($context, $child)) {
                yield $result;
            }
        }
    }

    /**
     * @param ContextInterface $context
     * @param RuleInterface $ast
     * @return null|RecordInterface
     */
    private function reduce(ContextInterface $context, RuleInterface $ast): ?RecordInterface
    {
        $process = $this->resolve($context, $ast);

        return $process instanceof \Generator ? $this->run($context, $process) : $process;
    }

    /**
     * @param ContextInterface $context
     * @param RuleInterface $ast
     * @return mixed|\Traversable|void
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function resolve(ContextInterface $context, RuleInterface $ast)
    {
        foreach ($this->builders as $builder) {
            if ($builder->match($ast)) {
                return $builder->reduce($context, $ast);
            }
        }

        $error = \sprintf('Unrecognized rule %s in (%s)', $ast->getName(), $ast);
        throw (new InternalException($error))->throwsIn($context->getFile(), $ast->getOffset());
    }

    /**
     * @param ContextInterface $ctx
     * @param \Generator $process
     * @return mixed|null
     */
    private function run(ContextInterface $ctx, \Generator $process)
    {
        while ($process->valid()) {
            $value = $process->current();

            switch (true) {
                case $value instanceof RuleInterface:
                    $value = $this->reduce($ctx, $value);
                    break;

                case $value instanceof TypeNameInterface:
                    $value = $ctx->create($value);
                    break;

                case \is_string($value):
                    $value = $ctx->create(TypeName::fromString($value));
                    break;
            }

            $process->send($value);
        }

        return $process->getReturn();
    }
}
