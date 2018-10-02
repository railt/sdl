<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder;

use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\Builder\Definition\Definition;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\IR\Type;

/**
 * Class DefinitionBuilder
 */
class DefinitionBuilder extends BaseBuilder
{
    /**
     * @var string[]
     */
    private const TYPE_DEFINITIONS = [
        'ObjectDefinition'    => Type::OBJECT,
        'SchemaDefinition'    => Type::SCHEMA,
        'DirectiveDefinition' => Type::DIRECTIVE,
    ];

    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return (bool)(self::TYPE_DEFINITIONS[$rule->getName()] ?? false);
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return mixed|\Generator|void
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule)
    {
        /** @var Definition $def */
        $def = yield $rule->first('> #TypeDefinition');
    }
}
