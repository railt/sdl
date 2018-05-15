<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Context;

use Railt\SDL\Stack\CallStackInterface;

/**
 * Class Context
 */
abstract class Context implements ContextInterface
{
    /**
     * @var CallStackInterface
     */
    private $stack;

    /**
     * @var ProvidesTypes
     */
    private $container;

    /**
     * Context constructor.
     * @param CallStackInterface $stack
     */
    public function __construct(CallStackInterface $stack)
    {
        $this->stack = $stack;
        $this->container = new Container($this);
    }

    /**
     * @return CallStackInterface
     */
    public function getStack(): CallStackInterface
    {
        return $this->stack;
    }

    /**
     * @return ProvidesTypes
     */
    public function getTypes(): ProvidesTypes
    {
        return $this->container;
    }
}
