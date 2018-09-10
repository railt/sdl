<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR;

/**
 * Class TypeHint
 */
class TypeHint implements TypeHintInterface
{
    /**
     * @var int
     */
    private $modifiers = 0;

    /**
     * @param int ...$values
     * @return self|$this
     */
    public function withModifiers(int ...$values): self
    {
        foreach ($values as $value) {
            $this->modifiers |= $value;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isNonNull(): bool
    {
        return (bool)($this->modifiers & static::IS_NOT_NULL);
    }

    /**
     * @return bool
     */
    public function isList(): bool
    {
        return (bool)($this->modifiers & static::IS_LIST);
    }

    /**
     * @return bool
     */
    public function isListOfNonNulls(): bool
    {
        return (bool)($this->modifiers & static::IS_LIST_OF_NOT_NULL);
    }

    /**
     * @return int
     */
    public function getModifiers(): int
    {
        return $this->modifiers;
    }
}
