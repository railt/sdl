<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Definition;

use Railt\GraphQL\AST\Node;
use Railt\GraphQL\AST\Proxy\DescriptionProxyTrait;
use Railt\GraphQL\AST\Proxy\DirectiveProxyTrait;
use Railt\GraphQL\AST\Proxy\NameProxyTrait;
use Railt\GraphQL\AST\Proxy\TypeHintProxyTrait;
use Railt\GraphQL\AST\TypeHint\TypeHintInterface;

/**
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
class FieldNode extends Node implements TypeHintInterface
{
    use NameProxyTrait;
    use TypeHintProxyTrait;
    use DirectiveProxyTrait;
    use DescriptionProxyTrait;

    /**
     * @var array|ArgumentNode[]
     */
    public $arguments = [];

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if ($value instanceof ArgumentNode) {
            $this->arguments[] = $value;

            return true;
        }

        return parent::each($value);
    }
}
