<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Definition;

use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class Argument
 */
class Argument implements DefinitionArgumentInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var TypeNameInterface
     */
    private $type;

    /**
     * Argument constructor.
     * @param string $name
     * @param TypeNameInterface $type
     */
    public function __construct(string $name, TypeNameInterface $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return TypeNameInterface
     */
    public function getHint(): TypeNameInterface
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName() . ': ' . $this->getHint()->getFullyQualifiedName();
    }
}
