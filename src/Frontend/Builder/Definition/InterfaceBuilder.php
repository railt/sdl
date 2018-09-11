<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Definition;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\AST\Definition\InterfaceDefinitionNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class InterfaceBuilder
 */
class InterfaceBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|InterfaceDefinitionNode $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $interface = new TypeDefinition($ast->getFullName());
        $interface->in($file, $ast->getOffset());

        $interface->type = Type::of(Type::INTERFACE);
        $interface->description = $ast->getDescription();

        $this->loadInterfaces($ast, $interface);

        yield from $this->loadDirectives($ast, $interface);
        yield from $this->loadFields($ast, $interface);

        return $interface;
    }

    /**
     * @param InterfaceDefinitionNode $ast
     * @param TypeDefinition $object
     */
    protected function loadInterfaces(InterfaceDefinitionNode $ast, TypeDefinition $object): void
    {
        $object->implements = [];

        foreach ($ast->getInterfaces() as $interface) {
            $object->implements[] = $interface;
        }
    }

    /**
     * @param InterfaceDefinitionNode $ast
     * @param TypeDefinition $object
     * @return \Generator
     */
    protected function loadFields(InterfaceDefinitionNode $ast, TypeDefinition $object): \Generator
    {
        $object->fields = [];

        foreach ($ast->getFieldNodes() as $field) {
            $object->fields[] = yield $field;
        }
    }
}
