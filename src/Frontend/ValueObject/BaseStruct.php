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
 * Class BaseStruct
 */
abstract class BaseStruct implements \JsonSerializable
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->render($this->value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function render($value): string
    {
        switch (\gettype($value)) {
            case 'boolean':
                return $value ? 'true' : 'false';
            case 'array':
                return json_encode($value);
            case 'object':
                return \get_class($value) . '#' . \spl_object_hash($value);
            case 'NULL':
                return 'null';
            default:
                return (string)$value;
        }
    }

    /**
     * @return bool
     */
    public function isScalar(): bool
    {
        return \is_scalar($this->value);
    }
}
