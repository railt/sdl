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
 * @property mixed $value
 */
class Value extends Definition implements ValueInterface
{
    /**
     * @var string
     */
    private const DEFAULT_VALUE_KEY = 'value';

    /**
     * @var bool
     */
    private $isScalar = false;

    /**
     * Value constructor.
     * @param mixed $value
     */
    public function __construct($value)
    {
        if (\is_scalar($value)) {
            $this->isScalar = true;
            $value = [self::DEFAULT_VALUE_KEY => $value];
        }

        parent::__construct($value);
    }

    /**
     * @return array|bool|float|int|mixed|null|string
     */
    public function getValue()
    {
        if ($this->isScalar) {
            return $this->get(self::DEFAULT_VALUE_KEY);
        }

        return $this->getAttributes();
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }
}
