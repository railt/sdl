<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Spec;

use Railt\SDL\CompilerInterface;
use Phplrt\Visitor\VisitorInterface;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Interface SpecificationInterface
 */
interface SpecificationInterface extends VisitorInterface
{
    /**
     * @param CompilerInterface $compiler
     * @return void
     */
    public function load(CompilerInterface $compiler): void;

    /**
     * @param iterable|Node|Node[] $ast
     * @return iterable|Node|Node[]
     */
    public function execute(iterable $ast): iterable;
}
