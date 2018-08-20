<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Common;

use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\Rule;
use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition\Behaviour\ProvidesTypeIndication as Hint;
use Railt\SDL\Ast\ProvidesName;
use Railt\SDL\Ast\Support\TypeNameProvider;

/**
 * Class TypeHintNode
 */
class TypeHintNode extends Rule implements ProvidesName
{
    use TypeNameProvider;

    /**
     * @var int
     */
    private $modifiers = 0;

    /**
     * TypeHintNode constructor.
     * @param string $name
     * @param array $children
     * @param int $offset
     */
    public function __construct(string $name, array $children = [], int $offset = 0)
    {
        parent::__construct($name, $children, $offset);

        foreach ($this->analyze($this->getChild(0)) as $child) {
            switch ($child) {
                case 'List':
                    $this->modifiers |= Hint::IS_LIST;
                    break;

                case 'NonNull':
                    $this->modifiers |= $this->isList() ? Hint::IS_LIST_OF_NOT_NULL : Hint::IS_NOT_NULL;
                    break;
            }
        }
    }

    /**
     * @param RuleInterface|NodeInterface $rule
     * @return \Generator
     */
    private function analyze(RuleInterface $rule): \Generator
    {
        $name = $rule->getName();

        if ($name !== 'TypeName') {
            yield $name;
            yield from $this->analyze($rule->getChild(0));
        }
    }

    /**
     * @return bool
     */
    public function isList(): bool
    {
        return (bool)($this->modifiers & Hint::IS_LIST);
    }

    /**
     * @return bool
     */
    public function isNonNull(): bool
    {
        return (bool)($this->modifiers & Hint::IS_NOT_NULL);
    }

    /**
     * @return bool
     */
    public function isListOfNonNulls(): bool
    {
        return (bool)($this->modifiers & Hint::IS_LIST_OF_NOT_NULL);
    }

    /**
     * @return int
     */
    public function getModifiers(): int
    {
        return $this->modifiers;
    }

    /**
     * @return null|TypeNameNode|NodeInterface
     */
    protected function getTypeNameNode(): ?TypeNameNode
    {
        return $this->first('TypeName', 3);
    }
}
