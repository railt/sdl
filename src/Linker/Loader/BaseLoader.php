<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker\Loader;

use Illuminate\Support\Str;

/**
 * Class BaseLoader
 */
abstract class BaseLoader implements LoaderInterface
{
    /**
     * @var array|string[]
     */
    protected $extensions = self::FILE_EXTENSIONS;

    /**
     * @param string $extension
     * @return LoaderInterface
     */
    public function addExtension(string $extension): LoaderInterface
    {
        $this->extensions[] = $extension;

        return $this;
    }

    /**
     * @param string $directory
     * @return mixed|string
     */
    protected function formatDirectory(string $directory)
    {
        $directory = \str_replace('\\', '/', $directory);

        return Str::endsWith($directory, '/') ? $directory : $directory . '/';
    }
}
