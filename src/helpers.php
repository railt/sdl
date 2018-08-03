<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

if (! \function_exists('\\sdl')) {
    /**
     * @param string $fileOrSources
     * @param bool $filename
     * @return \Railt\Reflection\Contracts\Document
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Io\Exception\NotReadableException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    function sdl(string $fileOrSources, bool $filename = false): \Railt\Reflection\Contracts\Document
    {
        $file = $filename ? \Railt\Io\File::fromPathname($fileOrSources) : \Railt\Io\File::fromSources($fileOrSources);

        return (new \Railt\SDL\Compiler())->compile($file);
    }
}


if (! function_exists('trait_uses_recursive')) {
    /**
     * Returns all traits used by a trait and its traits.
     *
     * @param string $trait
     * @return array
     */
    function trait_uses_recursive($trait): array
    {
        $traits = \class_uses($trait);

        foreach ($traits as $trait) {
            $traits += \trait_uses_recursive($trait);
        }

        return $traits;
    }
}


if (! function_exists('class_uses_recursive')) {
    /**
     * Returns all traits used by a class, its parent classes and trait of their traits.
     *
     * @param object|string $class
     * @return array
     */
    function class_uses_recursive($class): array
    {
        if (\is_object($class)) {
            $class = \get_class($class);
        }

        $results = [];

        foreach (\array_reverse(\class_parents($class)) + [$class => $class] as $class) {
            $results += \trait_uses_recursive($class);
        }

        return \array_unique($results);
    }
}


if (! function_exists('class_basename')) {
    /**
     * Get the class "basename" of the given object / class.
     *
     * @param string|object $class
     * @return string
     */
    function class_basename($class): string
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(\str_replace('\\', '/', $class));
    }
}
