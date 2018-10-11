<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Interceptor\Factory;

/**
 * Class Process
 */
class Process
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * Process constructor.
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param ContextInterface $ctx
     * @param mixed $result
     * @return array
     */
    public function run(ContextInterface $ctx, $result): array
    {
        if ($result instanceof \Generator) {
            return $this->await($ctx, $result);
        }

        return $this->factory->resolve($ctx, $result);
    }

    /**
     * @param ContextInterface $ctx
     * @param \Generator $process
     * @return array
     */
    public function await(ContextInterface $ctx, \Generator $process): array
    {
        while ($process->valid()) {
            [$ctx, $value] = $this->factory->resolve($ctx, $process->current());

            $process->send($value);
        }

        if ($value = $process->getReturn()) {
            return $this->factory->resolve($ctx, $value);
        }

        return [$ctx, $value];
    }
}
