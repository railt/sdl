<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\AST\TypeNameNode;
use Railt\SDL\Frontend\Context;
use Railt\SDL\Frontend\Context\ContextInterface;

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
     * @return mixed|void
     */
    public function reduce(ContextInterface $context, RuleInterface $rule)
    {
        /** @var TypeNameNode $namespace */
        $namespace = $rule->first('> #TypeName');

        yield $namespace->toTypeName(true);
    }
}
