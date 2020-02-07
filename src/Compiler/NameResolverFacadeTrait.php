<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Backend\NameResolver\HumanReadableResolver;
use Railt\SDL\Backend\NameResolver\NameResolverInterface;

/**
 * Trait NameResolverFacadeTrait
 */
trait NameResolverFacadeTrait
{
    /**
     * @var NameResolverInterface
     */
    private NameResolverInterface $resolver;

    /**
     * @return void
     */
    private function bootNameResolverFacadeTrait(): void
    {
        $this->resolver = new HumanReadableResolver();
    }

    /**
     * @param NameResolverInterface $resolver
     * @return $this
     */
    public function setNameResolver(NameResolverInterface $resolver): self
    {
        $this->resolver = $resolver;

        return $this;
    }

    /**
     * @return NameResolverInterface
     */
    public function getNameResolver(): NameResolverInterface
    {
        return $this->resolver;
    }
}
