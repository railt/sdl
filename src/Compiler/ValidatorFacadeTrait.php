<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\SDL\Validator\Factory;
use Railt\SDL\Validator\ValidatorInterface;

/**
 * Trait ValidatorFacadeTrait
 */
trait ValidatorFacadeTrait
{
    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @return void
     */
    private function bootValidatorFacadeTrait(): void
    {
        $this->validator = new Factory();
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @param DefinitionInterface $definition
     * @return void
     */
    public function assertValid(DefinitionInterface $definition): void
    {
        $this->validator->assert($definition);
    }
}
