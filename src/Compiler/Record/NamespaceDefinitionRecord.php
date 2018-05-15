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
use Railt\SDL\Compiler\Component\ContextComponent;
use Railt\SDL\Compiler\Component\InnerDefinitionsComponent;
use Railt\SDL\Compiler\Component\NameComponent;
use Railt\SDL\Compiler\Component\PriorityComponent;
use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Class NamespaceRecordDefinition
 */
class NamespaceDefinitionRecord extends Record
{
    /**
     * NamespaceDefinitionRecord constructor.
     * @param LocalContextInterface $context
     * @param RuleInterface $ast
     */
    public function __construct(LocalContextInterface $context, RuleInterface $ast)
    {
        parent::__construct($context, $ast);

        //
        // All definitions should provide highest priority of the
        // assembly for the arrangement at the top of the heap.
        //
        $this->add(new PriorityComponent(PriorityComponent::DEFINITION));

        //
        // Namespace should provide a new context.
        //
        // If namespace does not contain a body ("namespace Name"), then
        // the context should not rollback after analysis this record and
        // should extend to all subsequent records.
        //
        $ctx = new ContextComponent($context, $this->getNewContext($context, $ast), $this->shouldRollback($ast));

        $this->add($ctx);




        $this->add(InnerDefinitionsComponent::fromAst($ast));
    }

    /**
     * @param LocalContextInterface $context
     * @param RuleInterface $ast
     * @return LocalContextInterface
     */
    private function getNewContext(LocalContextInterface $context, RuleInterface $ast): LocalContextInterface
    {
        $name  = NameComponent::fromAst($context, $ast->find('#TypeName', 0));

        return $context->global()->create($context->getFile(), $name->getName());
    }

    /**
     * @param RuleInterface $ast
     * @return bool
     */
    private function shouldRollback(RuleInterface $ast): bool
    {
        return $ast->find('#ChildrenDefinitions', 0) instanceof RuleInterface;
    }
}
