<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System;

use Railt\SDL\Compiler\Component\Dependencies;
use Railt\SDL\Compiler\Component\TypeName;
use Railt\SDL\Compiler\Record\RecordInterface;
use Railt\SDL\ECS\EntityInterface;
use Railt\SDL\ECS\System;

/**
 * Class DependenciesResolver
 */
class DependenciesResolver extends System
{
    /**
     * @param EntityInterface $entity
     */
    public function resolve(EntityInterface $entity): void
    {
        $this->entity($entity)
            ->provides(TypeName::class, Dependencies::class)
            ->then(function(RecordInterface $record, TypeName $name, Dependencies $dependencies) {
                echo 'Type ' . $name . "\n";

                foreach ($dependencies->all() as $dependency) {
                    echo '  - requires ' . $dependency->getName() . ' in ' .
                        ((string)$dependency->getContext()->getName() ?: '/') . "\n";
                }
            });
    }
}
