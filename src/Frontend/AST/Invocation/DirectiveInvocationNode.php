<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Invocation;

use Railt\Parser\Ast\Rule;
use Railt\Reflection\Contracts\TypeInterface;
use Railt\Reflection\Type;
use Railt\SDL\Frontend\AST\ProvidesName;
use Railt\SDL\Frontend\AST\ProvidesOpcode;
use Railt\SDL\Frontend\AST\ProvidesType;
use Railt\SDL\Frontend\AST\Support\TypeNameProvider;
use Railt\SDL\Frontend\Context;
use Railt\SDL\Frontend\IR\JoinedOpcode;
use Railt\SDL\Frontend\IR\Opcode\AttachOpcode;
use Railt\SDL\Frontend\IR\Opcode\CompareOpcode;
use Railt\SDL\Frontend\IR\Opcode\FetchDeepOpcode;
use Railt\SDL\Frontend\IR\Opcode\NewOpcode;
use Railt\SDL\Frontend\IR\Value\TypeValue;

/**
 * Class DirectiveInvocationNode
 */
class DirectiveInvocationNode extends Rule implements ProvidesType, ProvidesName, ProvidesOpcode
{
    use TypeNameProvider;

    /**
     * @param Context $context
     * @return iterable
     */
    public function getOpcodes(Context $context): iterable
    {
        /** @var JoinedOpcode $definition */
        $definition = yield new FetchDeepOpcode($this->getFullNameValue(), $context->current());
        yield new CompareOpcode($definition, new TypeValue($this->getType(), $definition->getOffset()));

        $parent = $context->create();

        $invocation = yield new NewOpcode($definition, $parent);
        yield new AttachOpcode($invocation, $parent);
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return Type::of(Type::DIRECTIVE);
    }
}
