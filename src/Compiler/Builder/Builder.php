<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Builder;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\Behaviour\ProvidesTypeIndication;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\SDL\Compiler\Ast\Value\ValueInterface;
use Railt\SDL\Compiler\Builder\Common\ValueBuilder;
use Railt\SDL\Compiler\Builder\Common\ValueInvocation;
use Railt\SDL\Compiler\Factory;
use Railt\SDL\Compiler\Pipeline;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class Builder
 */
abstract class Builder implements BuilderInterface
{
    /**
     * @var Pipeline
     */
    protected $when;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * Builder constructor.
     * @param Pipeline $pipeline
     * @param Factory $factory
     */
    public function __construct(Pipeline $pipeline, Factory $factory)
    {
        $this->when    = $pipeline;
        $this->factory = $factory;
    }

    /**
     * @param Definition|ProvidesTypeIndication $from
     * @param ValueInterface $value
     * @return array|mixed
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    protected function valueOf(ProvidesTypeIndication $from, ValueInterface $value)
    {
        $result = (new ValueBuilder($from))->valueOf($value);

        $this->when->runtime(function() use ($result) {
            (new ValueInvocation())->invoke($result);
        });

        return $result;
    }

    /**
     * @param TypeDefinition $parent
     * @param TypeDefinition $definition
     * @param array $types
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    protected function shouldBeTypeOf(TypeDefinition $parent, TypeDefinition $definition, array $types): void
    {
        foreach ($types as $allowed) {
            if ($definition::getType()->is($allowed)) {
                return;
            }
        }

        $error = \vsprintf('%s should be one of {%s}, but %s given', [
            $parent, \implode(', ', $types), $definition
        ]);

        throw (new TypeConflictException($error))
            ->throwsIn($parent->getFile(), $parent->getLine(), $parent->getColumn());
    }

    /**
     * @param string $type
     * @param TypeDefinition $from
     * @return TypeDefinition
     * @throws \Railt\Reflection\Exception\TypeNotFoundException
     */
    protected function load(string $type, TypeDefinition $from): TypeDefinition
    {
        $dict = $from->getDictionary();

        return $dict->get($type, $from);
    }

    /**
     * @param RuleInterface $ast
     * @param Definition $parent
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    protected function dependent(RuleInterface $ast, Definition $parent): Definition
    {
        return $this->factory->build($ast, $parent);
    }
}
