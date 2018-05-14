<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Loader;

use Railt\Io\File;
use Railt\Io\Readable;

/**
 * Class DirectoryLoader
 */
class DirectoryLoader extends BaseLoader
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
        $this->directory = $this->formatDirectory($directory);
    }

    /**
     * @param string $type
     * @return null|Readable
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function fetch(string $type): ?Readable
    {
        return \is_file($this->directory . $type)
            ? File::fromPathname($this->directory . $type)
            : null;
    }
}
