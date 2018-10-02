<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Common;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\Builder\BaseBuilder;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\IR\SymbolTable\TypeNamePrimitive;
use Railt\SDL\IR\SymbolTable\Value;
use Railt\SDL\IR\SymbolTable\ValueInterface;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\Type\Name;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class TypeNameBuilder
 */
class TypeNameBuilder extends BaseBuilder
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'TypeName';
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return TypeNameInterface
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule): TypeNameInterface
    {
        return $this->getTypeName($rule);
    }

    /**
     * @param RuleInterface $rule
     * @return TypeNameInterface
     */
    protected function getTypeName(RuleInterface $rule): TypeNameInterface
    {
        $atRoot = $rule->first('> #AtRoot') instanceof RuleInterface;

        return Name::fromArray($this->getNameChunks($rule), $atRoot);
    }

    /**
     * @param RuleInterface $rule
     * @return array|string[]
     */
    private function getNameChunks(RuleInterface $rule): array
    {
        $result = [];

        /** @var LeafInterface $leaf */
        foreach ($rule->find('> :T_NAME') as $leaf) {
            $result[] = $leaf->getValue();
        }

        return $result;
    }
}
