<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Validator;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;

/**
 * Interface ValidatorInterface
 */
interface ValidatorInterface
{
    /**
     * @param DefinitionInterface $type
     * @return void
     */
    public function assert(DefinitionInterface $type): void;
}
