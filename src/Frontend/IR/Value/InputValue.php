<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR\Value;

/**
 * Class InputValue
 */
class InputValue extends AbstractValue
{
    /**
     * InputValue constructor.
     * @param iterable $values
     * @param int $offset
     */
    public function __construct(iterable $values, int $offset = 0)
    {
        parent::__construct($this->getCached($values), $offset);
    }

    /**
     * @param iterable $values
     * @return iterable
     */
    private function getCached(iterable $values): iterable
    {
        $result = [];

        foreach ($values as $k => $v) {
            $result[] = [$k, $v];
        }

        return $result;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $result = [];

        /**
         * @var ValueInterface $key
         * @var ValueInterface $value
         */
        foreach ($this->getValue() as $key => $value) {
            $result[] = $key->toString() . ': ' . (string)$value;
        }

        return \sprintf('{%s}', \implode(', ', $result));
    }

    /**
     * @return iterable
     */
    public function getValue(): iterable
    {
        foreach (parent::getValue() as [$key, $value]) {
            yield $key => $value;
        }
    }
}
