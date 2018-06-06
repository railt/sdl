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
     * Source code example:
     * <code>
     *  type X(a: A, b: B)
     *         ^^^^^^^^^^
     * </code>
     *
     * AST example:
     * <code>
     *  <Rule name="TypeArguments">
     *      <Rule name="TypeArgument">...</Rule>
     *  </Rule>
     * </code>
     *
     * @param RuleInterface $ast
     * @param LocalContextInterface $context
     */
    public function addTypeArguments(RuleInterface $ast, LocalContextInterface $context): void
    {
        \assert($ast->getName() === '#TypeArguments');

        foreach ($ast->getChildren() as $implements) {
            $this->addTypeArgument($implements, $context);
        }
    }

    /**
     * Source code example:
     * <code>
     *  type X(a: A, b: B)
     *         ^^^^
     * </code>
     *
     * AST example:
     * <code>
     *  <Rule name="TypeArgument">...</Rule>
     * </code>
     *
     * @param RuleInterface $argument
     * @param LocalContextInterface $context
     */
    public function addTypeArgument(RuleInterface $argument, LocalContextInterface $context): void
    {
        $this->ast($argument, '#TypeName', function (RuleInterface $argument) use ($context): void {
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
     * Source code example:
     * <code>
     *  type X implements A, B, C
     *                    ^^^^^^^
     * </code>
     *
     * AST example:
     * <code>
     *  <Rule name="Implements">...</Rule>
     * </code>
     *
     * @param RuleInterface $interfaces
     * @param LocalContextInterface $context
     */
    public function addImplementations(RuleInterface $interfaces, LocalContextInterface $context): void
    {
        \assert($interfaces->getName() === '#Implements');

        foreach ($interfaces->getChildren() as $interface) {
            $this->addTypeInvocation($interface, $context);
        }
    }

    /**
     * Source code example:
     * <code>
     *  type X implements A(a: X), B, C {
     *                    ^^^^^^^^^^
     *      field(arg: X(b: Y)): Z(c: S(d: Some))
     *                 ^^^^^^^        ^^^^^^^^^^
     *                           ^^^^^^^^^^^^^^^^
     *  }
     * </code>
     *
     * AST example:
     * <code>
     *  <Rule name="TypeInvocation">...</Rule>
     * </code>
     *
     * @param RuleInterface $invocations
     * @param LocalContextInterface $context
     */
    public function addTypeInvocation(RuleInterface $invocations, LocalContextInterface $context): void
    {
        $this->ast($invocations, '#TypeName', function (RuleInterface $name) use ($context): void {
            $this->add(TypeName::fromAst($name), $context);
        });

        $this->ast($invocations, '#TypeInvocationArguments', function (RuleInterface $args) use ($context): void {
            foreach ($args->getChildren() as $arg) {
                $this->ast($arg, '#TypeInvocation', function (RuleInterface $invocation) use ($context): void {
                    $this->addTypeInvocation($invocation, $context);
                });
            }
        });
    }

    /**
     * Source code example:
     * <code>
     *  type X {
     *      a: Type, b: [Type!]
     *      ^^^^^^^  ^^^^^^^^^^
     *  }
     * </code>
     *
     * AST example:
     * <code>
     *  <Rule name="FieldDefinitions">...</Rule>
     * </code>
     *
     * @param RuleInterface $fields
     * @param LocalContextInterface $context
     */
    public function addFields(RuleInterface $fields, LocalContextInterface $context): void
    {
        \assert($fields->getName() === '#FieldDefinitions');

        foreach ($fields->getChildren() as $field) {
            $this->addField($field, $context);
        }
    }

    /**
     * @param RuleInterface $directives
     * @param LocalContextInterface $context
     */
    public function addDirectives(RuleInterface $directives, LocalContextInterface $context): void
    {
        \assert($directives->getName() === '#Directives');

        foreach ($directives->getChildren() as $directive) {
            $this->addDirective($directive, $context);
        }
    }

    /**
     * @param RuleInterface $directive
     * @param LocalContextInterface $context
     */
    public function addDirective(RuleInterface $directive, LocalContextInterface $context): void
    {
        $this->ast($directive, '#TypeName', function (RuleInterface $argument) use ($context): void {
            $this->add(TypeName::fromAst($argument), $context);
        });
    }

    /**
     * Source code example:
     * <code>
     *  type X {
     *      a: Type
     *      ^^^^^^^
     *  }
     * </code>
     *
     * AST example:
     * <code>
     *  <Rule name="FieldDefinition">...</Rule>
     * </code>
     *
     * @param RuleInterface $field
     * @param LocalContextInterface $context
     */
    public function addField(RuleInterface $field, LocalContextInterface $context): void
    {
        \assert($field->getName() === '#FieldDefinition');

        $this->ast($field, '#FieldArguments', function (RuleInterface $args) use ($context): void {
            $this->addFieldArguments($args, $context);
        });

        $this->ast($field, '#TypeHint', function (RuleInterface $hint) use ($context): void {
            $this->addTypeInvocation($hint->find('#TypeInvocation', 2), $context);
        });

        $this->ast($field, '#Directives', function (RuleInterface $directives) use ($context): void {
            $this->addDirectives($directives, $context);
        });
    }

    /**
     * Source code example:
     * <code>
     *  type X {
     *      a(arg: Type, arg2: Type2): Type
     *       ^^^^^^^^^^^^^^^^^^^^^^^^
     *  }
     * </code>
     *
     * AST example:
     * <code>
     *  <Rule name="FieldArguments">...</Rule>
     * </code>
     *
     * @param RuleInterface $arguments
     * @param LocalContextInterface $context
     */
    public function addFieldArguments(RuleInterface $arguments, LocalContextInterface $context): void
    {
        \assert($arguments->getName() === '#FieldArguments');

        foreach ($arguments->getChildren() as $argument) {
            $this->addFieldArgument($argument, $context);
        }
    }

    /**
     * Source code example:
     * <code>
     *  type X {
     *      a(arg: Type, arg2: Type2): Type
     *        ^^^^^^^^^  ^^^^^^^^^^^
     *  }
     * </code>
     *
     * AST example:
     * <code>
     *  <Rule name="ArgumentDefinition">...</Rule>
     * </code>
     *
     * @param RuleInterface $argument
     * @param LocalContextInterface $context
     */
    public function addFieldArgument(RuleInterface $argument, LocalContextInterface $context): void
    {
        $this->ast($argument, '#TypeHint', function (RuleInterface $hint) use ($context): void {
            $this->addTypeInvocation($hint->find('#TypeInvocation', 2), $context);
        });

        $this->ast($argument, '#Directives', function (RuleInterface $directives) use ($context): void {
            $this->addDirectives($directives, $context);
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
