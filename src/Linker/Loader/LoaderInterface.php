<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker\Loader;

use Railt\Io\Readable;

/**
 * Interface LoaderInterface
 */
interface LoaderInterface
{
    /**
     * @var array|string[]
     */
    public const FILE_EXTENSIONS = [
        '.graphqls',
        '.graphql',
        '.gql',
    ];

    /**
     * @param string $extension
     * @return LoaderInterface
     */
    public function addExtension(string $extension): LoaderInterface;

    /**
     * @param string $type
     * @return null|Readable
     */
    public function fetch(string $type): ?Readable;
}
