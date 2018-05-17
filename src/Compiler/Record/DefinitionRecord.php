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
use Railt\SDL\Compiler\Component\NameComponent;
use Railt\SDL\Compiler\Component\VisibilityComponent;
use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Class DefinitionRecord
 */
class DefinitionRecord extends Record
{
    /**
     * DefinitionRecord constructor.
     * @param LocalContextInterface $context
     * @param RuleInterface $ast
     */
    public function __construct(LocalContextInterface $context, RuleInterface $ast)
    {
        parent::__construct($context, $ast);

        //
        // All definitions except "namespace" and "schema" must contain
        // the name that are located at the root of the Abstract Syntax Tree.
        //
        $this->add(new NameComponent($context, $ast->find('#TypeName', 0)));

        //
        // Provides visibility
        //
        $this->add(new VisibilityComponent($context->isPublic()));
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return static::DEFINITION;
    }
}
