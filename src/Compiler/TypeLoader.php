<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Io\File;
use Railt\Io\Readable;
use Railt\SDL\Exception\TypeNotFoundException;
use Railt\SDL\Stack\CallStack;

/**
 * Class TypeLoader
 */
class TypeLoader implements Loader
{
    /**
     * @var array|\Closure[]
     */
    private $loaders = [];

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * TypeLoader constructor.
     * @param CallStack $stack
     */
    public function __construct(CallStack $stack)
    {
        $this->stack = $stack;
    }

    /**
     * @param callable $then
     * @return TypeLoader
     */
    public function addLoader(callable $then): Loader
    {
        $this->loaders[] = $then;

        return $this;
    }

    /**
     * @param string $type
     * @return Readable
     * @throws TypeNotFoundException
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function fetch(string $type): Readable
    {
        foreach ($this->loaders as $loader) {
            $file = $loader($type);

            switch (true) {
                case \is_string($file):
                    return File::fromPathname($file);

                case $file instanceof Readable:
                    return $file;
            }
        }

        $error = 'Type %s could not be loaded';
        throw new TypeNotFoundException(\sprintf($error, $type), $this->stack);
    }
}
