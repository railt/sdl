<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\NameResolver;

/**
 * Class RawResolver
 */
class RawResolver implements NameResolverInterface
{
    /**
     * @param string $name
     * @param array $args
     * @return string
     */
    public function resolve(string $name, array $args = []): string
    {
        return $name . '<' . \implode(',', $args) . '>';
    }
}
