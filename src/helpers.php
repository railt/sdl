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
