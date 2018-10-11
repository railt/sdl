<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Naming;

use Railt\SDL\Exception\SemanticException;
use Railt\SDL\IR\SymbolTable\ValueInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class Strategy
 */
class Strategy implements StrategyInterface
{
    /**
     * @var \Closure
     */
    private $callback;

    /**
     * Strategy constructor.
     * @param \Closure $callback
     */
    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param TypeNameInterface $name
     * @param iterable|ValueInterface[] $arguments
     * @return string
     */
    public function resolve(TypeNameInterface $name, iterable $arguments): string
    {
        return $this->verified(($this->callback)($name, $arguments));
    }

    /**
     * @param string $name
     * @return string
     */
    protected function verified(string $name): string
    {
        if (! \preg_match('/^[_a-zA-Z][_a-zA-Z0-9]*$/', $name)) {
            $error = \sprintf('Names must match /^[_a-zA-Z][_a-zA-Z0-9]*$/ but "%s" does not.', $name);
            throw new SemanticException($error);
        }

        return $name;
    }
}
