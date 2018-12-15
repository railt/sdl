<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\TypeLoader;

use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Reflection\Contracts\Definition;

/**
 * Class DirectoryLoader
 */
class DirectoryLoader extends TypeLoader
{
    /**
     * @var string
     */
    private $directory;

    /**
     * DirectoryLoader constructor.
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    /**
     * @param string $type
     * @param Definition|null $from
     * @return Readable|null
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function load(string $type, Definition $from = null): ?Readable
    {
        foreach ($this->fileExtensions as $extension) {
            $pathName = $this->directory . '/' . $type . '.' . $extension;

            if (\is_file($pathName)) {
                return File::fromPathname($pathName);
            }
        }

        return null;
    }
}
