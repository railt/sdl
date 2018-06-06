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
 * Class EntityResolver
 */
class EntityResolver
{
    /**
     * @var EntityInterface
     */
    private $entity;

    /**
     * @var array|string[]
     */
    private $components = [];

    /**
     * @var bool
     */
    private $matched = true;

    /**
     * EntityResolver constructor.
     * @param EntityInterface $entity
     */
    public function __construct(EntityInterface $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param string ...$components
     * @return EntityResolver
     */
    public function provides(string ...$components): self
    {
        $this->components = \array_merge($this->components, $components);

        foreach ($components as $component) {
            if (! $this->entity->has($component)) {
                $this->matched = false;
                break;
            }
        }

        return $this;
    }

    /**
     * @param string $of
     * @return EntityResolver
     */
    public function instanceOf(string $of): self
    {
        if (! ($this->entity instanceof $of)) {
            $this->matched = false;
        }

        return $this;
    }

    /**
     * @return iterable|ComponentInterface[]
     */
    private function getParameters(): iterable
    {
        foreach ($this->components as $component) {
            yield $this->entity->get($component);
        }
    }

    /**
     * @param \Closure $then
     * @return EntityResolver
     */
    public function then(\Closure $then): self
    {
        if ($this->matched) {
            $parameters = \array_merge([$this->entity], \iterable_to_array($this->getParameters()));

            $then(...$parameters);
        }

        return $this;
    }

    /**
     * @param \Closure $then
     * @return EntityResolver
     */
    public function otherwise(\Closure $then): self
    {
        if (! $this->matched) {
            $then($this->entity);
        }

        return $this;
    }
}
