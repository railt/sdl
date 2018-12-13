<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder\Definition;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Definition\SchemaDefinition;

/**
 * Class SchemaBuilder
 */
class SchemaBuilder extends TypeDefinitionBuilder
{
    /**
     * @return Definition
     */
    public function build(): Definition
    {
        $schema = $this->bind(new SchemaDefinition($this->document, $this->findName()));

        foreach ($this->ast as $child) {
            $this->async(function () use ($child, $schema) {
                $this->buildField($child, $schema);
            });
        }

        return $schema;
    }

    /**
     * @param RuleInterface $rule
     * @param SchemaDefinition $definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function buildField(RuleInterface $rule, SchemaDefinition $definition): void
    {
        switch ($rule[0]->getValue()) {
            case 'query':
                $definition->withQuery($this->loadFieldType($rule[1]));
                break;

            case 'mutation':
                $definition->withMutation($this->loadFieldType($rule[1]));
                break;

            case 'subscription':
                $definition->withSubscription($this->loadFieldType($rule[1]));
                break;
        }
    }

    /**
     * @param RuleInterface $rule
     * @return string|null
     */
    private function loadFieldType(RuleInterface $rule)
    {
        return $rule->getValue();
    }
}
