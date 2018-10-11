<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Context;

/**
 * Class Container
 */
class Container
{
    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Container constructor.
     * @param ContextInterface $context
     * @param mixed $value
     */
    public function __construct(ContextInterface $context, $value = null)
    {
        $this->context = $context;
        $this->value = $value;
    }

    /**
     * @return ContextInterface
     */
    public function getContext(): ContextInterface
    {
        return $this->context;
    }

    /**
     * @param ContextInterface $context
     */
    public function setContext(ContextInterface $context): void
    {
        $this->context = $context;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }
}
