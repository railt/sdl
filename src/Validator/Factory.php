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
 * Class Factory
 */
class Factory implements ValidatorInterface
{
    /**
     * @var string[]|ValidatorInterface[]
     */
    private const DEFAULT_VALIDATORS = [
        SchemaValidator::class,
    ];

    /**
     * @var array|ValidatorInterface[]
     */
    private array $validators = [];

    /**
     * Factory constructor.
     */
    public function __construct()
    {
        foreach (self::DEFAULT_VALIDATORS as $class) {
            $this->validators[] = new $class($this);
        }
    }

    /**
     * @param DefinitionInterface $type
     * @return bool
     */
    public function match(DefinitionInterface $type): bool
    {
        return true;
    }

    /**
     * @param DefinitionInterface $type
     * @return void
     */
    public function assert(DefinitionInterface $type): void
    {
        foreach ($this->validators as $validator) {
            if ($validator->match($type)) {
                $validator->assert($type);

                return;
            }
        }
    }
}
