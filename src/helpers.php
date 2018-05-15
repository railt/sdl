<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


if (! \function_exists('\\spl_object_id')) {
    /**
     * This function returns a unique identifier for the object.
     * The object id is unique for the lifetime of the object. Once the
     * object is destroyed, its id may be reused for other objects. This
     * behavior is similar to spl_object_hash().
     *
     * @param object $object Any object.
     * @return int An integer identifier that is unique for each currently existing
     *  object and is always the same for each object.
     * @throws Exception
     */
    function spl_object_id($object): int
    {
        static $pool = [];

        $id = \spl_object_hash($object);

        if (! \array_key_exists($id, $pool)) {
            \ob_start();
            \debug_zval_dump($object);
            \preg_match('/^.+\)#(\d+)/isu', \ob_get_clean(), $matches);

            $pool[$id] = (int)($matches[1] ?? \random_int(4096, \PHP_INT_MAX));
        }

        return (int)$pool[$id];
    }
}


if (! \function_exists('\\iterator_values')) {
    /**
     * Returns the keys of an iterable.
     *
     * <code>
     *   \iterator_values(['a' => 0, 'b' => 1, 'c' => 2])
     *   // => [0, 1, 2]
     * </code>
     *
     * @param iterable $iterable Iterable to get keys from
     * @return \Traversable
     */
    function iterator_values(iterable $iterable): \Traversable
    {
        foreach ($iterable as $_ => $value) {
            yield $value;
        }
    }
}


if (! \function_exists('\\iterator_keys')) {
    /**
     * Returns the keys of an iterable.
     *
     * <code>
     *   \iterator_keys(['a' => 0, 'b' => 1, 'c' => 2])
     *   // => ['a', 'b', 'c']
     * </code>
     *
     * @param iterable $iterable Iterable to get keys from
     * @return \Traversable
     */
    function iterator_keys(iterable $iterable): \Traversable
    {
        foreach ($iterable as $key => $_) {
            yield $key;
        }
    }
}


if (! \function_exists('\\iterable_to_array')) {
    /**
     * Converts an iterable into an array, without preserving keys.
     *
     * Not preserving the keys is useful, because iterators do not necessarily have
     * unique keys and/or the key type is not supported by arrays.
     *
     * <code>
     *  \iterable_to_array(new ArrayIterator(['a' => 1, 'b' => 2, 'c' => 3]))
     *  // => []
     * </code>
     *
     * @param iterable $iterable
     * @return array
     */
    function iterable_to_array(iterable $iterable): array
    {
        $result = [];

        foreach ($iterable as $key => $value) {
            $result[] = $value;
        }

        return $result;
    }
}


if (! \function_exists('\\iterable_map')) {
    /**
     * <code>
     *  \iterable_map(new ArrayIterator(['a' => 1, 'b' => 2, 'c' => 3]), function(int $v, string $k) { ... });
     * </code>
     *
     * @param iterable $iterable
     * @param Closure $applicator
     * @return array
     */
    function iterable_map(iterable $iterable, \Closure $applicator): iterable
    {
        foreach ($iterable as $key => $value) {
            $result = $applicator($value, $key);

            if (\is_iterable($result)) {
                yield from $result;
            } else {
                yield $result;
            }
        }
    }
}


if (! \function_exists('\\iterable_or_null')) {
    /**
     * @param iterable $iterable
     * @return iterable|null
     */
    function iterable_or_null(iterable $iterable): ?iterable
    {
        switch (true) {
            case $iterable instanceof \Iterator:
                return $iterable->valid() ? $iterable : null;
            case $iterable instanceof \Traversable:
                return \iterator_count($iterable) ? $iterable : null;
            default:
                return \count($iterable) ? $iterable : null;
        }
    }
}
