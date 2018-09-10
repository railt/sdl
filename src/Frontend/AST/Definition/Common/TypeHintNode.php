<?php
/**
 * This file is part of sdl package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition\Common;

use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\Rule;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\IR\TypeHint;

/**
 * Class TypeHintNode
 */
class TypeHintNode extends Rule
{
    /**
     * @var TypeHint
     */
    private $hint;

    /**
     * TypeHintNode constructor.
     * @param string $name
     * @param array $children
     * @param int $offset
     */
    public function __construct(string $name, array $children = [], int $offset = 0)
    {
        parent::__construct($name, $children, $offset);

        $this->hint = new TypeHint();

        foreach ($this->analyze($this->getChild(0)) as $child) {
            switch ($child) {
                case 'List':
                    $this->hint->withModifiers(TypeHint::IS_LIST);
                    break;

                case 'NonNull':
                    $modifier = $this->hint->isList()
                        ? TypeHint::IS_LIST_OF_NOT_NULL
                        : TypeHint::IS_NOT_NULL;

                    $this->hint->withModifiers($modifier);
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
     * @return int
     */
    public function getModifiers(): int
    {
        return $this->hint->getModifiers();
    }
}
