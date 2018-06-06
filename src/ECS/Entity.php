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
 * Class Entity
 */
class Entity implements EntityInterface
{
    /**
     * @var ComponentInterface[]
     */
    protected $components = [];

    /**
     * {@inheritdoc}
     */
    public function add(ComponentInterface ...$components): EntityInterface
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function has($component): bool
    {
        return \array_key_exists($this->key($component), $this->components);
    }

    /**
     * {@inheritdoc}
     */
    public function all(): iterable
    {
        yield from \array_values($this->components);
    }

    /**
     * {@inheritdoc}
     */
    public function get($component): ?ComponentInterface
    {
        return $this->components[$this->key($component)] ?? null;
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
