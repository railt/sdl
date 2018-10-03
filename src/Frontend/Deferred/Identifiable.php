<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Deferred;

use Railt\SDL\Frontend\Definition\DefinitionInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Interface Identifiable
 */
interface Identifiable
{
    /**
     * @return DefinitionInterface
     */
    public function getDefinition(): DefinitionInterface;
}
