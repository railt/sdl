<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Processor\Invocation;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\SDL\Compiler\Processor\BaseProcessor;

/**
 * Class DirectiveInvocationProcessor
 */
class DirectiveInvocationProcessor extends BaseProcessor
{
    /**
     * @param RuleInterface $rule
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $rule): ?TypeDefinition
    {
        return null;
    }
}
