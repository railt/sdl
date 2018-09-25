<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Record;

use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\Finder;
use Railt\SDL\Frontend\Type\TypeInterface;
use Railt\SDL\Frontend\Type\TypeNameInterface;

/**
 * Class Record
 */
class Record implements RecordInterface, \JsonSerializable
{
    /**
     * @var TypeNameInterface
     */
    private $name;

    /**
     * @var RuleInterface
     */
    private $ast;

    /**
     * @var array|ArgumentInterface[]
     */
    private $arguments = [];
    /**
     * @var TypeInterface
     */
    private $type;

    /**
     * Record constructor.
     * @param TypeNameInterface $name
     * @param TypeInterface $type
     * @param RuleInterface $ast
     */
    public function __construct(TypeNameInterface $name, TypeInterface $type, RuleInterface $ast)
    {
        $this->name = $name;
        $this->ast = $ast;
        $this->type = $type;
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @return iterable|array
     */
    public function getArguments(): iterable
    {
        return $this->arguments;
    }

    /**
     * @param ArgumentInterface $argument
     * @return RecordInterface
     */
    public function addArgument(ArgumentInterface $argument): RecordInterface
    {
        $this->arguments[$argument->getName()] = $argument;

        return $this;
    }

    /**
     * @return TypeNameInterface
     */
    public function getName(): TypeNameInterface
    {
        return $this->name;
    }

    /**
     * @param string $query
     * @return Finder
     */
    public function find(string $query): Finder
    {
        return $this->ast->find($query);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'name'      => $this->name,
            'type'      => $this->type,
            'arguments' => $this->arguments,
        ];
    }
}
