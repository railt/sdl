<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Record;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Component\PriorityComponent;
use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Class InvocationRecord
 */
class InvocationRecord extends Record
{
    /**
     * DefinitionRecord constructor.
     * @param LocalContextInterface $context
     * @param RuleInterface $ast
     */
    public function __construct(LocalContextInterface $context, RuleInterface $ast)
    {
        parent::__construct($context, $ast);

        $this->add(new PriorityComponent(PriorityComponent::INVOCATION));
    }
}
