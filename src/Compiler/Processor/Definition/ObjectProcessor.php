<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Processor\Definition;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Definition\ObjectDefinition;
use Railt\SDL\Compiler\Ast\Definition\ObjectDefinitionNode;
use Railt\SDL\Compiler\Processor\DefinitionProcessor;

/**
 * Class ObjectProcessor
 */
class ObjectProcessor extends DefinitionProcessor
{
    /**
     * @param RuleInterface|ObjectDefinitionNode $rule
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function resolve(RuleInterface $rule): Definition
    {
        $object = new ObjectDefinition($this->document, $rule->getTypeName());

        foreach ($rule->getFields() as $field) {
            $object->withField($this->build($field));
        }

        return $object;
    }
}
