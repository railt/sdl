<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\CompilerInterface;
use Railt\SDL\Spec\Railt;
use Railt\SDL\Spec\SpecificationInterface;

/**
 * @mixin CompilerInterface
 */
trait SpecificationFacadeTrait
{
    /**
     * @var SpecificationInterface
     */
    protected SpecificationInterface $spec;

    /**
     * @param SpecificationInterface|null $spec
     * @return $this
     */
    public function setSpecification(SpecificationInterface $spec = null): self
    {
        $this->spec = $spec ?? new Railt();

        return $this;
    }

    /**
     * @return void
     */
    private function bootSpecificationFacadeTrait(): void
    {
        $this->spec->load($this);
    }

    /**
     * @return SpecificationInterface
     */
    public function getSpecification(): SpecificationInterface
    {
        return $this->spec;
    }
}
