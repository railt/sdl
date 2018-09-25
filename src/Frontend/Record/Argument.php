<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Record;

use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Type\TypeInterface;

/**
 * Class Argument
 */
class Argument implements ArgumentInterface, \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var TypeInterface
     */
    private $type;

    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * Argument constructor.
     * @param string $name
     * @param TypeInterface $type
     * @param ContextInterface $context
     */
    public function __construct(string $name, TypeInterface $type, ContextInterface $context)
    {
        $this->name = $name;
        $this->type = $type;
        $this->context = $context->current();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type->getName(),
            'ctx'  => $this->context,
        ];
    }
}
