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
use Railt\SDL\Compiler\Common\TypeName;
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

        $this->name = $this->typeName($ast, function (TypeName $name): void {
            if ($name->isGlobal()) {
                $error = 'The type name can not be declared as global';
                throw new SemanticException($error, $this->getCallStack());
            }
        });
    }

    /**
     * @param RuleInterface $ast
     * @param \Closure $then
     * @return TypeName
     */
    protected function typeName(RuleInterface $ast, \Closure $then): TypeName
    {
        /** @var RuleInterface $typeName */
        $typeName = $ast->find('#TypeName', 0);

        \assert($typeName !== null, 'Internal Error: Bad name extraction logic of ' . $ast);

        $this->getCallStack()->pushAst($this->getFile(), $typeName);

        $result = $then(TypeName::fromAst($typeName));

        $this->getCallStack()->pop();

        return $result;
    }
}
