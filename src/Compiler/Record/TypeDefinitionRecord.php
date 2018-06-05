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
use Railt\SDL\Compiler\TypeName;
use Railt\SDL\Compiler\Context\LocalContextInterface;
use Railt\SDL\Exception\SemanticException;

/**
 * Class TypeDefinitionRecord
 */
abstract class TypeDefinitionRecord extends Record
{
    /**
     * @var TypeName
     */
    private $name;

    /**
     * TypeDefinitionRecord constructor.
     * @param LocalContextInterface $context
     * @param RuleInterface $ast
     */
    public function __construct(LocalContextInterface $context, RuleInterface $ast)
    {
        parent::__construct($context, $ast);

        $this->name = $this->withAst($ast->find('#TypeName', 0), function(RuleInterface $ast) {
            $name = TypeName::fromAst($ast);

            if ($name->isGlobal()) {
                $error = \sprintf('The type "%s" should not be registered as global', $name);
                throw new SemanticException($error, $this->getCallStack());
            }

            return $name;
        });
    }
}
