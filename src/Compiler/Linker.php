<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Io\Readable;
use Railt\SDL\Compiler\Loader\LoaderInterface;
use Railt\SDL\Compiler\Record\RecordInterface;

/**
 * Class Linker
 */
class Linker
{
    /**
     * @var LoaderInterface[]|\SplStack
     */
    private $loaders;

    /**
     * @var ProvidesTypes
     */
    private $container;

    /**
     * @var PrebuiltTypes
     */
    private $builder;

    /**
     * TypeLoader constructor.
     * @param ProvidesTypes $container
     * @param PrebuiltTypes $builder
     */
    public function __construct(ProvidesTypes $container, PrebuiltTypes $builder)
    {
        $this->loaders   = new \SplStack();
        $this->container = $container;
        $this->builder   = $builder;
    }

    /**
     * @param LoaderInterface $loader
     * @return Linker
     */
    public function addLoader(LoaderInterface $loader): self
    {
        $this->loaders->push($loader);

        return $this;
    }

    /**
     * @param string $type
     * @return null|RecordInterface
     */
    public function fetch(string $type): ?RecordInterface
    {
        return $this->fetchIf($type, function (string $type): ?RecordInterface {
            return $this->loadIf($type, function (Readable $file) use ($type): ?RecordInterface {
                $container = $this->builder->extract($file);

                return $container->has($type) ? $container->get($type) : null;
            });
        });
    }

    /**
     * @param string $type
     * @param \Closure $otherwise
     * @return null|RecordInterface
     */
    private function fetchIf(string $type, \Closure $otherwise): ?RecordInterface
    {
        if ($this->container->has($type)) {
            return $this->container->get($type);
        }

        return $otherwise($type);
    }

    /**
     * @param string $type
     * @param \Closure $load
     * @return null|RecordInterface
     */
    private function loadIf(string $type, \Closure $load): ?RecordInterface
    {
        foreach ($this->files($type) as $file) {
            if ($result = $load($file)) {
                return $result;
            }
        }

        return null;
    }

    /**
     * @param string $type
     * @return \Traversable|Readable[]
     */
    private function files(string $type): \Traversable
    {
        foreach ($this->loaders as $loader) {
            /** @var Readable $file */
            $file = $loader->fetch($type);

            if ($file) {
                yield $file;
            }
        }
    }
}
