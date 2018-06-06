<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Component\Dependencies\Dependency;
use Railt\SDL\Compiler\Context\LocalContextInterface;
use Railt\SDL\Compiler\Support\AstFinder;
use Railt\SDL\ECS\ComponentInterface;

/**
 * Class Dependencies
 */
class Dependencies implements ComponentInterface
{
    use AstFinder;

    /**
     * @var array|Dependency[]
     */
    private $dependencies = [];

    /**
     * @param RuleInterface $ast
     * @param LocalContextInterface $context
     */
    public function addTypeArgument(RuleInterface $ast, LocalContextInterface $context): void
    {
        $this->ast($ast, '#TypeName', function (RuleInterface $argument) use ($context): void {
            $this->add(TypeName::fromAst($argument), $context);
        });
    }

    /**
     * @param TypeName $name
     * @param LocalContextInterface $context
     */
    public function add(TypeName $name, LocalContextInterface $context): void
    {
        $this->dependencies[] = new Dependency($name, $context);
    }

    /**
     * @param RuleInterface $ast
     * @param LocalContextInterface $context
     */
    public function addTypeInvocation(RuleInterface $ast, LocalContextInterface $context): void
    {
        $this->ast($ast, '#TypeName', function (RuleInterface $name) use ($context): void {
            $this->add(TypeName::fromAst($name), $context);
        });

        $this->ast($ast, '#TypeInvocationArguments', function (RuleInterface $args) use ($context): void {
            foreach ($args->getChildren() as $arg) {
                $this->ast($arg, '#TypeInvocation', function (RuleInterface $invocation) use ($context): void {
                    $this->addTypeInvocation($invocation, $context);
                });
            }
        });
    }

    /**
     * @return iterable|Dependency[]
     */
    public function all(): iterable
    {
        return $this->dependencies;
    }
}
