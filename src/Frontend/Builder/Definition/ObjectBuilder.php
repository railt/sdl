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
use Railt\SDL\Frontend\Ast\Definition\ObjectDefinitionNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class ObjectBuilder
 */
class ObjectBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|ObjectDefinitionNode $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $object = new TypeDefinition($ast->getFullName());
        $object->in($file, $ast->getOffset());

        $object->type        = Type::OBJECT;
        $object->description = $ast->getDescription();

        $this->loadInterfaces($ast, $object);

        yield from $this->loadFields($ast, $object);

        return $object;
    }

    /**
     * @param ObjectDefinitionNode $ast
     * @param TypeDefinition $object
     * @return \Generator
     */
    protected function loadFields(ObjectDefinitionNode $ast, TypeDefinition $object): \Generator
    {
        $object->fields = [];

        foreach ($ast->getFieldNodes() as $field) {
            $object->fields[] = yield $field;
        }
    }

    /**
     * @param ObjectDefinitionNode $ast
     * @param TypeDefinition $object
     */
    protected function loadInterfaces(ObjectDefinitionNode $ast, TypeDefinition $object): void
    {
        $object->implements = [];

        foreach ($ast->getInterfaces() as $interface) {
            $object->implements[] = $interface;
        }
    }
}
