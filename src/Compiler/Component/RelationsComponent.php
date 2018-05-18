<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Component\Relations\Relation;
use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Class RelationsComponent
 */
class RelationsComponent implements ComponentInterface
{
    /**
     * @var array|Relation[]
     */
    private $relations = [];

    /**
     * @var LocalContextInterface
     */
    private $context;

    /**
     * RelationsComponent constructor.
     * @param LocalContextInterface $context
     */
    public function __construct(LocalContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * @return LocalContextInterface
     */
    public function getContext(): LocalContextInterface
    {
        return $this->context;
    }

    /**
     * @return iterable|Relation[]
     */
    public function getRelations(): iterable
    {
        yield from $this->relations;
    }

    /**
     * @param RuleInterface $fields
     * @return RelationsComponent
     */
    public function addFields(?RuleInterface $fields): RelationsComponent
    {
        if ($fields !== null) {
            foreach ($fields->getChildren() as $ast) {
                $return = $ast->find('#ReturnTypeDefinition');

                $this->addTypeName($return->find('#TypeName', 1));
            }
        }

        return $this;
    }

    /**
     * @param null|RuleInterface $typeName
     * @return RelationsComponent
     */
    public function addTypeName(?RuleInterface $typeName): RelationsComponent
    {
        if ($typeName !== null) {
            $name = new NameComponent($this->context->current(), $typeName);

            $this->addRelation($name->getName(), $typeName);
        }

        return $this;
    }

    /**
     * @param null|RuleInterface $interfaces
     * @return RelationsComponent
     */
    public function addInterfaces(?RuleInterface $interfaces): RelationsComponent
    {
        if ($interfaces !== null) {
            foreach ($interfaces->getChildren() as $typeName) {
                $this->addTypeName($typeName);
            }
        }

        return $this;
    }

    /**
     * @param string $type
     * @param RuleInterface $ast
     */
    private function addRelation(string $type, RuleInterface $ast): void
    {
        $position = $this->context->getFile()->getPosition($ast->getOffset());

        $this->relations[] = new Relation($type, $position);
    }
}
