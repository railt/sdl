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
 * Class Validator
 */
abstract class Validator implements ValidatorInterface
{
    /**
     * @var Factory
     */
    private Factory $factory;

    /**
     * Validator constructor.
     *
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param DefinitionInterface $definition
     * @return void
     */
    protected function validate(DefinitionInterface $definition): void
    {
        $this->factory->assert($definition);
    }
}
