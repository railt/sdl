<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Record;

use Railt\SDL\Compiler\Component\ComponentInterface;

/**
 * A record is composed from components. As such, it is essentially a
 * collection object for components. Sometimes, the records in a program will
 * mirror the actual objects in the SDL, but this is not necessary.
 *
 * Components are simple value objects that contain data relevant to the
 * entity. Records with similar functionality will have instances of the
 * same components. So we might have a position component:
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
interface RecordInterface
{
    /**
     * Add a component to the entity.
     *
     * <code>
     * //
     * // type User implements Interface
     * // {
     * //     id: ID!
     * // }
     * //
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
     * @return RecordInterface|$this A reference to the record. This enables the chaining of
     * calls to add, to make creating and configuring records cleaner. e.g.
     */
    public function add(ComponentInterface ...$components): RecordInterface;

    /**
     * Remove a component from the record.
     *
     * @param string|ComponentInterface $component The class of the component to be removed.
     * @return ComponentInterface|null The component, or null if the component doesn't exist in the record.
     */
    public function remove($component): ?ComponentInterface;

    /**
     * Does the record have a component of a particular type.
     *
     * @param string|ComponentInterface $component The class of the component sought or an instance of.
     * @return bool Returns true if the record has a component of the type, false if not.
     */
    public function has($component): bool;

    /**
     * Get a component from the record.
     *
     * @param string|ComponentInterface $component The class or instance of the component requested.
     * @return ComponentInterface|null The component, or null if none was found.
     */
    public function get($component): ?ComponentInterface;

    /**
     * Get all components from the record.
     *
     * @return iterable|ComponentInterface[] An iterable containing all the components that are on the record.
     */
    public function all(): iterable;
}
