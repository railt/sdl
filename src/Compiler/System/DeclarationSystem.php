<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System;

use Railt\SDL\Compiler\Component\TypeName;
use Railt\SDL\Compiler\Record\TypeDefinitionRecord;
use Railt\SDL\ECS\EntityInterface;
use Railt\SDL\ECS\System;
use Railt\SDL\Exception\SemanticException;

/**
 * Class DeclarationSystem
 */
class DeclarationSystem extends System
{
    /**
     * @param EntityInterface $entity
     */
    public function resolve(EntityInterface $entity): void
    {
        $this->entity($entity)
            ->provides(TypeName::class)
            ->instanceOf(TypeDefinitionRecord::class)
            ->then(function (TypeDefinitionRecord $type, TypeName $name): void {
                if ($name->isGlobal()) {
                    $error = \sprintf('The type "%s" should not be registered as global', $name);

                    throw new SemanticException($error, $type->getContext()->getCallStack());
                }
            });
    }
}
