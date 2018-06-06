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
 * Interface ContainerInterface
 */
interface ContainerInterface
{
    /**
     * @param EntityInterface ...$entities
     */
    public function resolve(EntityInterface ...$entities): void;

    /**
     * @param SystemInterface|string $system
     * @return ContainerInterface
     */
    public function addSystem(string $system): self;
}
