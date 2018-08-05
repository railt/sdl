<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Invocation;

use Railt\Parser\Ast\Rule;
use Railt\Reflection\Contracts\Invocation\TypeInvocation;
use Railt\Reflection\Document;
use Railt\Reflection\Invocation\DirectiveInvocation;

/**
 * Class DirectiveNode
 */
class DirectiveNode extends Rule
{
    /**
     * @param Document $document
     * @return TypeInvocation|DirectiveInvocation
     */
    public function getTypeInvocation(Document $document): TypeInvocation
    {
        $directive = new DirectiveInvocation($document, $this->getDirectiveName());
        $directive->withOffset($this->getOffset());

        return $directive;
    }

    /**
     * @return string
     */
    public function getDirectiveName(): string
    {
        return $this->first('TypeName', 1)->getTypeName();
    }

    /**
     * @return iterable|DirectiveArgumentNode[]
     */
    public function getDirectiveArguments(): iterable
    {
        $arguments = $this->first('DirectiveArguments', 1);

        if ($arguments) {
            foreach ($arguments as $argument) {
                yield $argument;
            }
        }
    }
}
