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
use Railt\SDL\Frontend\Builder\Factory;
use Railt\SDL\IR\Definition;

/**
 * Class Builder
 */
class Builder
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * Builder constructor.
     */
    public function __construct()
    {
        $this->factory = new Factory();
    }

    /**
     * @param Readable $readable
     * @param RuleInterface $ast
     * @return \Generator|RuleInterface[]|Definition[]
     * @throws \LogicException
     */
    public function build(Readable $readable, RuleInterface $ast)
    {
        $iterator = $this->forEach($readable, $ast);

        while ($iterator->valid()) {
            [$key, $value] = [$iterator->key(), $iterator->current()];

            if ($value instanceof RuleInterface) {
                yield from $children = $this->build($readable, $value);
                $iterator->send($children->getReturn());
                continue;
            }

            $iterator->send(yield $key => $value);
        }

        return $iterator->getReturn();
    }

    /**
     * @param Readable $readable
     * @param RuleInterface $ast
     * @return mixed|null
     * @throws \LogicException
     */
    public function reduce(Readable $readable, RuleInterface $ast)
    {
        $iterator = $this->build($readable, $ast);

        while ($iterator->valid()) {
            $iterator->send($iterator->current());
        }

        return $iterator->getReturn();
    }

    /**
     * @param Readable $readable
     * @param RuleInterface $ast
     * @return \Generator
     * @throws \LogicException
     */
    private function forEach(Readable $readable, RuleInterface $ast): \Generator
    {
        $builder = $this->factory->resolve($readable, $ast);
        $result  = $builder->build($readable, $ast);

        if (\is_iterable($result)) {
            yield from $result;
        }

        return $result instanceof \Generator ? $result->getReturn() : $result;
    }
}
