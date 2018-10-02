<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\Io\Exception\NotReadableException;
use Railt\Io\File;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Exception\CompilerException;

if (! \function_exists('sdl')) {
    /**
     * @param string $fileOrSources
     * @param bool $filename
     * @return Document
     * @throws CompilerException
     * @throws NotReadableException
     */
    function sdl(string $fileOrSources, bool $filename = false): Document
    {
        $file = $filename ? File::fromPathname($fileOrSources) : File::fromSources($fileOrSources);

        return (new Compiler())->compile($file);
    }
}

if (! \function_exists('object_to_string')) {
    /**
     * @param object $object
     * @return string
     */
    function object_to_string($object): string
    {
        \assert(\is_object($object));

        $hash = \function_exists('\\spl_object_id')
            ? \spl_object_id($object)
            : \spl_object_hash($object);

        if (is_renderable($object)) {
            return \sprintf('%s(%s)#%s', \get_class($object), (string)$object, $hash);
        }

        return \sprintf('%s#%s', \get_class($object), $hash);
    }
}


if (! \function_exists('is_renderable')) {
    /**
     * @param mixed $value
     * @return bool
     */
    function is_renderable($value): bool
    {
        return \is_scalar($value) || (\is_object($value) && \method_exists($value, '__toString'));
    }
}
