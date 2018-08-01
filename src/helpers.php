<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\Io\File;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Compiler;

if (! \function_exists('\\sdl')) {
    /**
     * @param string $fileOrSources
     * @param bool $filename
     * @return Document
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Io\Exception\NotReadableException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    function sdl(string $fileOrSources, bool $filename = false): Document
    {
        $file = $filename ? File::fromPathname($fileOrSources) : File::fromSources($fileOrSources);

        return (new Compiler())->compile($file);
    }
}
