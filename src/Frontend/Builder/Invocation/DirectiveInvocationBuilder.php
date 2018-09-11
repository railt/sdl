<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Invocation;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\AST\Invocation\DirectiveValueNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\TypeInvocation;

/**
 * Class DirectiveInvocationBuilder
 */
class DirectiveInvocationBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|DirectiveValueNode $ast
     * @return \Generator|mixed|void
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $directive = new TypeInvocation($ast->getFullName());

        yield from $this->loadArguments($ast, $directive);

        return $directive;
    }

    /**
     * @param DirectiveValueNode $ast
     * @param TypeInvocation $directive
     * @return \Generator
     */
    private function loadArguments(DirectiveValueNode $ast, TypeInvocation $directive): \Generator
    {
        $directive->arguments = [];

        foreach ($ast->getArgumentNodes() as $argument) {
            $directive->arguments[$argument->getFullName()] = yield $argument;
        }
    }
}
