<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\ECS;

/**
 * Class Container
 */
class Container implements ContainerInterface
{
    /**
     * @var array|SystemInterface
     */
    private $systems = [];

    /**
     * @var \Closure
     */
    private $constructor;

    /**
     * Container constructor.
     * @param \Closure $systemCreator
     */
    public function __construct(\Closure $systemCreator)
    {
        $this->constructor = $systemCreator;
    }

    /**
     * @param string $system
     * @return ContainerInterface
     */
    public function addSystem(string $system): ContainerInterface
    {
        $instance = ($this->constructor)($system);

        \assert($instance instanceof SystemInterface);

        $this->systems[] = $instance;

        return $this;
    }

    /**
     * @param EntityInterface ...$entities
     */
    public function resolve(EntityInterface ...$entities): void
    {
        foreach ($entities as $entity) {
            foreach ($this->systems as $system) {
                $system->resolve($entity);
            }
        }
    }
}
