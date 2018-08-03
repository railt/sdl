<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast;

use Railt\Parser\Ast\Rule;
use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition\Behaviour\ProvidesTypeIndication;

/**
 * Class TypeHintNode
 */
class TypeHintNode extends Rule
{
    /**
     * @var string
     */
    private const HINT_LIST = 'ListTypeHint';

    /**
     * @var string
     */
    private const HINT_SINGULAR = 'SingularTypeHint';

    /**
     * @var string
     */
    private const HINT_NOT_NULL = 'NonNull';
    /**
     * @var int
     */
    protected $modifiers = 0;
    /**
     * @var string
     */
    protected $definition;

    /**
     * TypeHintNode constructor.
     * @param string $name
     * @param array $children
     * @param int $offset
     */
    public function __construct(string $name, $children = [], int $offset = 0)
    {
        parent::__construct($name, $children, $offset);

        $inner = \reset($children);

        if ($this->isListTypeHint($inner)) {
            $this->parseListTypeHint($inner);
        } else {
            $this->parseInnerTypeHint($inner);
        }
    }

    /**
     * @param RuleInterface $rule
     * @return bool
     */
    private function isListTypeHint(RuleInterface $rule): bool
    {
        return $rule->getName() === self::HINT_LIST;
    }

    /**
     * @param RuleInterface $rule
     */
    private function parseListTypeHint(RuleInterface $rule): void
    {
        $this->modifiers |= ProvidesTypeIndication::IS_LIST;

        foreach ($rule->getChildren() as $child) {
            switch ($child->getName()) {
                case self::HINT_SINGULAR:
                    $this->parseInnerTypeHint($child);
                    break;

                case self::HINT_NOT_NULL:
                    $this->modifiers |= ProvidesTypeIndication::IS_LIST_OF_NOT_NULL;
                    break;
            }
        }
    }

    /**
     * @param RuleInterface $rule
     */
    private function parseInnerTypeHint(RuleInterface $rule): void
    {
        foreach ($rule->getChildren() as $child) {
            switch ($child->getName()) {
                case self::HINT_NOT_NULL:
                    $this->modifiers |= ProvidesTypeIndication::IS_NOT_NULL;
                    break;

                case 'TypeName':
                    /** @var TypeNameNode $child */
                    $this->definition = $child->getTypeName();
                    break;
            }
        }
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->definition;
    }

    /**
     * @return int
     */
    public function getModifiers(): int
    {
        return $this->modifiers;
    }
}
