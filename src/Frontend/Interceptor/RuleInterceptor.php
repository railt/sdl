<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Interceptor;

use Railt\Io\Readable;
use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;

/**
 * Class RuleInterceptor
 */
class RuleInterceptor extends BaseInterceptor
{
    /**
     * @param mixed $result
     * @return bool
     */
    public function match($result): bool
    {
        return $result instanceof RuleInterface;
    }

    /**
     * @param Readable $file
     * @param RuleInterface $node
     * @return mixed
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function apply(Readable $file, $node)
    {
        return $this->builder->reduce($file, $node);
    }
}
