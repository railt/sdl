<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR;

use Railt\Parser\Ast\RuleInterface;

/**
 * Class Deferred
 */
class Deferred
{
    /**
     * @var RuleInterface
     */
    private $rule;

    /**
     * @var callable
     */
    private $callback;

    /**
     * Deferred constructor.
     * @param RuleInterface $rule
     * @param callable $callback
     */
    public function __construct(RuleInterface $rule, callable $callback)
    {
        $this->rule     = $rule;
        $this->callback = $callback;
    }

    /**
     * @param mixed ...$args
     * @return \Generator
     */
    public function __invoke(...$args)
    {
        yield from $this->resolve(...$args);
    }

    /**
     * @param mixed ...$args
     * @return \Generator
     */
    public function resolve(...$args): \Generator
    {
        $result = ($this->callback)(...$args);

        if ($result instanceof \Generator) {
            yield from $result;
        } else {
            yield $this->rule => $result;
        }
    }
}
