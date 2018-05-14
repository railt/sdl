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
 * An entity is composed from components. As such, it is essentially a
 * collection object for components. Sometimes, the entities in a game will
 * mirror the actual objects in the SDL, but this is not necessary.
 *
 * Components are simple value objects that contain data relevant to the
 * entity. Entities with similar functionality will have instances of the
 * same components. So we might have a position component.
 *
 * <code>
 * class PositionComponent implements ComponentInterface
 * {
 *      private $file;
 *      private $offset = 0;
 *
 *      public function getPosition(): Position
 *      {
 *          return $this->file->getPosition($this->offset);
 *      }
 * }
 * </code>
 *
 * All entities that have a position in the file, will have an instance of the
 * position component. Systems operate on entities based on the components
 * they have.
 */
class Entity
{
    /**
     * @var ComponentInterface[]
     */
    private $components = [];

    /**
     * Entity constructor.
     * @param ComponentInterface ...$components
     */
    public function __construct(ComponentInterface ...$components)
    {
        $this->add(...$components);
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
     * @return Entity A reference to the entity. This enables the chaining of
     * calls to add, to make creating and configuring entities cleaner. e.g.
     */
    public function add(ComponentInterface ...$components): self
    {
        foreach ($components as $component) {
            $key = $this->key($component);

            \assert(! \array_key_exists($key, $this->components), "Component ${key} duplication");

            $this->components[$key] = $component;
        }

        return $this;
    }

    /**
     * @param string|ComponentInterface $component
     * @return string
     */
    private function key($component): string
    {
        \assert($component instanceof ComponentInterface || \is_string($component));

        return \is_string($component) ? $component : \get_class($component);
    }

    /**
     * Remove a component from the entity.
     *
     * @param string|ComponentInterface $component The class of the component to be removed.
     * @return ComponentInterface|null The component, or null if the component doesn't exist in the entity.
     */
    public function remove($component): ?ComponentInterface
    {
        $key    = $this->key($component);
        $result = $this->components[$key] ?? null;

        if ($this->has($component)) {
            unset($this->components[$key]);
        }

        return $result;
    }

    /**
     * Does the entity have a component of a particular type.
     *
     * @param string|ComponentInterface $component The class of the component sought or an instance of.
     * @return bool Returns true if the entity has a component of the type, false if not.
     */
    public function has($component): bool
    {
        return \array_key_exists($this->key($component), $this->components);
    }

    /**
     * @param Entity $entity
     * @return Entity
     */
    public function merge(self $entity): self
    {
        $this->components = \array_merge($this->components, $entity->components);

        return $this;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        foreach ($this->components as $key => $component) {
            $this->components[$key] = clone $this->components[$key];
        }
    }
}
