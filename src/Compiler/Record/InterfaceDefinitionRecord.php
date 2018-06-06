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
use Railt\SDL\Compiler\Component\Dependencies;
use Railt\SDL\Compiler\Component\TypeName;
use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Class InterfaceDefinitionRecord
 */
class InterfaceDefinitionRecord extends TypeDefinitionRecord
{
    /**
     * ObjectDefinitionRecord constructor.
     * @param LocalContextInterface $context
     * @param RuleInterface $ast
     */
    public function __construct(LocalContextInterface $context, RuleInterface $ast)
    {
        parent::__construct($context, $ast);

        // interface Type(arg: A)
        $this->ast($ast, '#TypeArguments', function (RuleInterface $ast) {
            foreach ($ast->getChildren() as $implements) {
                $this->dep()->addTypeArgument($implements, $this->getContext());
            }
        });

        $context->transact($this->get(TypeName::class), $this->getFile(), function () use ($ast) {
            // implements A & B & C
            $this->ast($ast, '#Implements', function (RuleInterface $ast) {
                foreach ($ast->getChildren() as $implements) {
                    $this->dep()->addTypeInvocation($implements, $this->getContext());
                }
            });
        });
    }
}
