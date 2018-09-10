<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;

/**
 * Interface BuilderInterface
 */
interface BuilderInterface
{
    /**
     * @param Readable $file
     * @param RuleInterface $ast
     * @return mixed|\Generator
     */
    public function build(Readable $file, RuleInterface $ast);
}
