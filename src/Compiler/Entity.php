<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Compiler\Component\ComponentInterface;

/**
 * Class Entity
 */
class Entity
{
    /**
     * @var \SplObjectStorage|ComponentInterface[]
     */
    private $components;

    /**
     * Entity constructor.
     * @param ComponentInterface ...$components
     */
    public function __construct(ComponentInterface ...$components)
    {
        $this->components = new \SplObjectStorage();
    }

    /**
     * Add a component to the entity.
     *
     * <code>
     * // type User implements Interface { id: ID! }
     *
     * $entity = new Entity(
     *      new Component/Ast($ast),
     *      new Component/Context('User'),
     *      new Component/Relation('Interface'),
     *      new Component/Name('User', 'Object'),
     *      new Component/Position($file, $offset),
     *      new Component/Priority(Priority::DEFINITION),
     * );
     * </code>
     *
     * @param ComponentInterface ...$components The component object to add.
     */
    public function add(ComponentInterface ...$components): void
    {
        foreach ($components as $component) {
            $this->components->attach($component);
        }
    }

    /**
     * @param string $component
     * @param \Closure $then
     * @return bool
     */
    private function componentByClass(string $component, \Closure $then): bool
    {
        foreach ($this->components as $needle) {
            if ($needle instanceof $component && $then($needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ComponentInterface|string $component
     */
    public function remove($component): void
    {
        \assert($component instanceof ComponentInterface || \is_string($component));

        switch (true) {
            case $component instanceof ComponentInterface:
                $this->components->detach($component);
                break;

            case \is_string($component):
                $this->componentByClass($component, function(ComponentInterface $needle): void {
                    $this->components->detach($needle);
                });
                break;
        }
    }

    public function has($component): bool
    {
        \assert($component instanceof ComponentInterface || \is_string($component));

        switch (true) {
            case $component instanceof ComponentInterface:
                return $this->components->contains($component);

            case \is_string($component):
                return $this->componentByClass($component, function(): bool {
                    return true;
                });
        }

        return false;
    }

    /**
     * @param string $component
     * @return bool
     */
    private function hasComponentClass(string $component): bool
    {
        foreach ($this->components as $needle) {
            if ($needle instanceof $component) {
                $this->components->detach($needle);
            }
        }
    }


    /**
     * @param Entity $entity
     */
    public function merge(Entity $entity): void
    {
        $this->components->addAll($entity->components);
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->components = clone $this->components;
    }
}
