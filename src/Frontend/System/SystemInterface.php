<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\System;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;

/**
 * Interface SystemInterface
 */
interface SystemInterface
{
    /**
     * @param RuleInterface $ast
     * @return bool
     */
    public function match(RuleInterface $ast): bool;

    /**
     * @param Readable $readable
     * @param RuleInterface $ast
     * @return mixed
     */
    public function apply(Readable $readable, RuleInterface $ast);
}
