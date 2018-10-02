<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Instruction;

use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\Builder\BaseBuilder;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class NamespaceBuilder
 */
class NamespaceBuilder extends BaseBuilder
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'NamespaceDefinition';
    }

    /**
     * @param ContextInterface $context
     * @param RuleInterface $rule
     * @return \Generator|ContextInterface
     */
    public function reduce(ContextInterface $context, RuleInterface $rule): \Generator
    {
        /** @var TypeNameInterface $name */
        $name = yield $rule->first('> #TypeName');

        yield $context->create($name->lock());
    }
}
