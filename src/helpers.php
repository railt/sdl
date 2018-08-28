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


if (! \function_exists('\\iterator_to_generator')) {
    /**
     * @param iterable $iterator
     * @return Generator
     */
    function iterator_to_generator(iterable $iterator): \Generator
    {
        yield from $iterator;
    }
}


if (! \function_exists('\\iterator_map')) {
    /**
     * @param iterable $iterator
     * @param Closure $map
     * @return Generator
     */
    function iterator_map(iterable $iterator, \Closure $map): \Generator
    {
        $generator = \iterator_to_generator($iterator);

        while ($generator->valid()) {
            $generator->send(yield $generator->key() => $map($generator->current()));
        }
    }
}


if (! \function_exists('\\iterator_reverse_map')) {
    /**
     * @param iterable $iterator
     * @param Closure $map
     * @return Generator
     */
    function iterator_reverse_map(iterable $iterator, \Closure $map): \Generator
    {
        $generator = \iterator_to_generator($iterator);

        while ($generator->valid()) {
            $generator->send($map(yield $generator->key() => $generator->current()));
        }
    }
}


if (! \function_exists('\\iterator_each')) {
    /**
     * @param iterable $iterator
     * @param Closure $each
     * @return Generator
     */
    function iterator_each(iterable $iterator, \Closure $each): \Generator
    {
        $generator = \iterator_to_generator($iterator);

        while ($generator->valid()) {
            $each($value = $generator->current());

            $generator->send(yield $generator->key() => $value);
        }
    }
}


if (! \function_exists('\\iterator_reverse_each')) {
    /**
     * @param iterable $iterator
     * @param Closure $each
     * @return Generator
     */
    function iterator_reverse_each(iterable $iterator, \Closure $each): \Generator
    {
        $generator = \iterator_to_generator($iterator);

        while ($generator->valid()) {
            $each($result = yield $generator->key() => $generator->current());

            $generator->send($result);
        }
    }
}


if (! \function_exists('\\iterator_filter')) {
    /**
     * @param iterable $iterator
     * @param Closure $filter
     * @return Generator
     */
    function iterator_reverse_filter(iterable $iterator, \Closure $filter): \Generator
    {
        $generator = \iterator_to_generator($iterator);

        while ($generator->valid()) {
            $next = $filter($result = $generator->current());

            $generator->send($next ? (yield $generator->key() => $result) : null);
        }
    }
}


if (! \function_exists('\\iterator_reverse_filter')) {
    /**
     * @param iterable $iterator
     * @param Closure $filter
     * @return Generator
     */
    function iterator_reverse_filter(iterable $iterator, \Closure $filter): \Generator
    {
        $generator = \iterator_to_generator($iterator);

        while ($generator->valid()) {
            $next = $filter($result = yield $generator->key() => $generator->current());

            $generator->send($next ? $result : null);
        }
    }
}
