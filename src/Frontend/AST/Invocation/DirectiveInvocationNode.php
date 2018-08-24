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
use Railt\SDL\Frontend\IR\Opcode;
use Railt\SDL\Frontend\IR\Opcode\CallOpcode;
use Railt\SDL\Frontend\IR\Opcode\CompareOpcode;
use Railt\SDL\Frontend\IR\Opcode\FetchOpcode;

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
        $current = $context->create();

        $fetched = yield new FetchOpcode($this->getFullName(), $current, false);
        yield new CompareOpcode($fetched, $this->getType());
        yield new CallOpcode($fetched);
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return Type::of(Type::DIRECTIVE);
    }
}
