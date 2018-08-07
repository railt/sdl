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
use Railt\SDL\Compiler\Factory;
use Railt\SDL\Compiler\Pipeline;

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
        return (new ValueBuilder($from))->valueOf($value);
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
