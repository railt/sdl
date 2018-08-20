<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System\Provider;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Definition\Behaviour\HasFields;
use Railt\SDL\Ast\ProvidesFieldNodes;
use Railt\SDL\Compiler\System\System;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class FieldSystem
 */
class FieldSystem extends System
{
    /**
     * @param Definition|HasFields $definition
     * @param RuleInterface $ast
     */
    public function resolve(Definition $definition, RuleInterface $ast): void
    {
        if ($ast instanceof ProvidesFieldNodes) {
            foreach ($ast->getFieldNodes() as $child) {
                $this->deferred(function () use ($definition, $child) {
                    /** @var Definition\Dependent\FieldDefinition $field */
                    $field = $this->process->build($child, $definition);

                    $this->linker(function () use ($definition, $field) {
                        if ($definition->hasField($field->getName())) {
                            throw $this->redeclareException($field);
                        }

                        $definition->withField($field);
                    });
                });
            }
        }
    }
}
