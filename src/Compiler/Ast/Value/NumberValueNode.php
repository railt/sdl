<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Value;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\Rule;
use Railt\SDL\Compiler\Parser;

/**
 * Class NumberValueNode
 */
class NumberValueNode extends Rule implements ValueInterface
{
    /**
     * @return string
     */
    public function toString(): string
    {
        return (string)$this->toPrimitive();
    }

    /**
     * @return float|int
     */
    public function toPrimitive()
    {
        return $this->parse();
    }

    /**
     * @return int|float
     */
    protected function parse()
    {
        /** @var LeafInterface $value */
        $value = $this->getChild(0);

        switch (true) {
            case $this->isHex($value):
                return $this->parseHex($value->getValue(1));

            case $this->isBinary($value):
                return $this->parseBin($value->getValue(1));

            case $this->isExponential($value):
                return $this->parseExponential($value->getValue());

            case $this->isFloat($value):
                return $this->parseFloat($value->getValue());

            case $this->isInt($value):
                return $this->parseInt($value->getValue());
        }

        return (float)$value->getValue();
    }

    /**
     * @param LeafInterface $leaf
     * @return bool
     */
    private function isHex(LeafInterface $leaf): bool
    {
        return $leaf->getName() === Parser::T_HEX_NUMBER;
    }

    /**
     * @param string $value
     * @return int
     */
    private function parseHex(string $value): int
    {
        return \hexdec($value);
    }

    /**
     * @param LeafInterface $leaf
     * @return bool
     */
    private function isBinary(LeafInterface $leaf): bool
    {
        return $leaf->getName() === Parser::T_BIN_NUMBER;
    }

    /**
     * @param string $value
     * @return int
     */
    private function parseBin(string $value): int
    {
        return \bindec($value);
    }

    /**
     * @param LeafInterface $leaf
     * @return bool
     */
    private function isExponential(LeafInterface $leaf): bool
    {
        return \substr_count(\mb_strtolower($leaf->getValue()), 'e') !== 0;
    }

    /**
     * @param string $value
     * @return float
     */
    private function parseExponential(string $value): float
    {
        return (float)$value;
    }

    /**
     * @param LeafInterface $leaf
     * @return bool
     */
    private function isFloat(LeafInterface $leaf): bool
    {
        return \substr_count($leaf->getValue(), '.') !== 0;
    }

    /**
     * @param string $value
     * @return float
     */
    private function parseFloat(string $value): float
    {
        return (float)$value;
    }

    /**
     * @param LeafInterface $leaf
     * @return bool
     */
    private function isInt(LeafInterface $leaf): bool
    {
        return $leaf->getName() === Parser::T_NUMBER && \substr_count($leaf->getValue(), '.') === 0;
    }

    /**
     * @param string $value
     * @return int
     */
    private function parseInt(string $value): int
    {
        return $value >> 0;
    }
}
