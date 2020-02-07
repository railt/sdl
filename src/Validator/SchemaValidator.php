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
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use Phplrt\Source\Exception\NotAccessibleException;
use Railt\SDL\Exception\TypeErrorException;

/**
 * Class SchemaValidator
 */
class SchemaValidator extends Validator
{
    /**
     * @var string
     */
    private const ERROR_SCHEMA_OPERATION_TYPE = 'Schema %s operation should be defined by GraphQL Object type';

    /**
     * @param DefinitionInterface $type
     * @return bool
     */
    public function match(DefinitionInterface $type): bool
    {
        return $type instanceof SchemaInterface;
    }

    /**
     * @param DefinitionInterface|SchemaInterface $schema
     * @return void
     * @throws TypeErrorException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function assert(DefinitionInterface $schema): void
    {
        $this->assertOperation(fn() => $schema->getQueryType(), 'query');
        $this->assertOperation(fn() => $schema->getMutationType(), 'mutation');
        $this->assertOperation(fn() => $schema->getSubscriptionType(), 'subscription');

        foreach ($schema->getTypeMap() as $type) {
            $this->validate($type);
        }

        foreach ($schema->getDirectives() as $directive) {
            $this->validate($directive);
        }
    }

    /**
     * @param \Closure $closure
     * @param string $operation
     * @return void
     * @throws TypeErrorException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    private function assertOperation(\Closure $closure, string $operation): void
    {
        try {
            $closure();
        } catch (\Throwable $e) {
            throw new TypeErrorException(\sprintf(self::ERROR_SCHEMA_OPERATION_TYPE, $operation));
        }
    }
}
