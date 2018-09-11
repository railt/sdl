<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Invocation;

use Railt\Io\Readable;
use Railt\SDL\IR\ValueInterface;

/**
 * Interface AstValueInterface
 */
interface AstValueInterface
{
    /**
     * @var string[]
     */
    public const VALUE_NODE_NAMES = [
        'ConstantValue',
        'BooleanValue',
        'NumberValue',
        'StringValue',
        'NullValue',
        'InputValue',
        'ListValue',
    ];

    /**
     * @return mixed
     */
    public function toPrimitive();

    /**
     * @param Readable $file
     * @return ValueInterface
     */
    public function toValue(Readable $file): ValueInterface;
}
