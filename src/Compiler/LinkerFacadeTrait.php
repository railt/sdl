<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Backend\Linker\LinkerInterface;
use Railt\SDL\Backend\Linker\Registry;

/**
 * Trait LinkerFacadeTrait
 */
trait LinkerFacadeTrait
{
    /**
     * @var LinkerInterface
     */
    protected LinkerInterface $linker;

    /**
     * @return void
     */
    private function bootLinkerFacadeTrait(): void
    {
        $this->linker = new Registry();
    }

    /**
     * {@inheritDoc}
     */
    public function autoload(callable $loader): LinkerInterface
    {
        return $this->linker->autoload($loader);
    }

    /**
     * @return LinkerInterface
     */
    public function getLinker(): LinkerInterface
    {
        return $this->linker;
    }

    /**
     * {@inheritDoc}
     */
    public function cancelAutoload(callable $loader): LinkerInterface
    {
        return $this->linker->cancelAutoload($loader);
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaders(): iterable
    {
        return $this->linker->getAutoloaders();
    }
}
