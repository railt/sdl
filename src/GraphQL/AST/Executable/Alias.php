<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Executable;

use Railt\GraphQL\AST\Node;
use Railt\GraphQL\AST\Proxy\NameProxyTrait;

/**
 * Class Alias
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
class Alias extends Node
{
    use NameProxyTrait;
}
