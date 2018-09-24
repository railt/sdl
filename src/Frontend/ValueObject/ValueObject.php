<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\ValueObject;

/**
 * Class ValueObject
 */
class ValueObject extends BaseStruct
{
    /**
     * ValueObject constructor.
     * @param iterable $attributes
     */
    public function __construct(iterable $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * @param iterable $attributes
     */
    public function setAttributes(iterable $attributes): void
    {
        foreach ($attributes as $attribute => $value) {
            $this->set($attribute, $value);
        }
    }

    /**
     * @param string|int $attribute
     * @param mixed $value
     * @return void
     */
    public function set($attribute, $value): void
    {
        $this->value[$attribute] = new ScalarValue($value);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return \array_map(function (BaseStruct $value) {
            return $value instanceof ValueObject ? $value->toArray() : $value->getValue();
        }, $this->value);
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->value;
    }

    /**
     * @param string|int $attribute
     * @return mixed|null
     */
    public function __get($attribute)
    {
        return $this->get($attribute);
    }

    /**
     * @param string|int $attribute
     * @param mixed $value
     */
    public function __set($attribute, $value)
    {
        $this->set($attribute, $value);
    }

    /**
     * @param string|int $attribute
     * @return mixed|null
     */
    public function get($attribute)
    {
        return $this->value[$attribute] ?? new ScalarValue(null);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
