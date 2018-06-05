<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Context\LocalContextInterface;
use Railt\SDL\Compiler\Dependency\Argument;

/**
 * Class Dependency
 */
class Dependency
{
    /**
     * @var TypeName
     */
    private $type;

    /**
     * @var LocalContextInterface
     */
    private $context;

    /**
     * @var array
     */
    private $params = [];

    /**
     * Dependency constructor.
     * @param LocalContextInterface $context
     * @param TypeName $type
     */
    public function __construct(LocalContextInterface $context, TypeName $type)
    {
        $this->type    = $type;
        $this->context = $context;
    }

    /**
     * @param RuleInterface $rule
     * @param LocalContextInterface $context
     * @return Dependency
     */
    public static function fromAst(RuleInterface $rule, LocalContextInterface $context): Dependency
    {
        \assert($rule->getName() === '#TypeInvocation',
            'Internal Error: Bad dependency root node ' . (string)$rule);

        $name = TypeName::fromAst($rule->find('#TypeName', 1));

        $instance = new static($context, $name);

        foreach (self::findArguments($rule, $context) as $name => $parameter) {
            $instance->params[$name] = $parameter;
        }

        return $instance;
    }

    /**
     * @param RuleInterface $rule
     * @param LocalContextInterface $context
     * @return iterable
     */
    private static function findArguments(RuleInterface $rule, LocalContextInterface $context): iterable
    {
        $arguments = $rule->find('#TypeArguments', 1);

        if ($arguments) {
            foreach ($arguments->getChildren() as $argument) {
                \assert($argument->getName() === '#ArgumentDefinition');

                $name  = $argument->getChild(0)->getChild(0)->getValue();
                $value = $argument->getChild(1);

                yield $name => Argument::fromAst($value, $context);
            }
        }
    }

    /**
     * @return iterable|Dependency[]
     */
    public function getParameters(): iterable
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'name'       => (string)$this->getType(),
            'context'    => (string)$this->getContext()->getName(),
            'parameters' => $this->params,
        ];
    }

    /**
     * @return TypeName
     */
    public function getType(): TypeName
    {
        return $this->type;
    }

    /**
     * @return LocalContextInterface
     */
    public function getContext(): LocalContextInterface
    {
        return $this->context;
    }
}
