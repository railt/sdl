<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Processor;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;

/**
 * Interface ProcessorInterface
 */
interface ProcessorInterface
{
    /**
     * @param RuleInterface $rule
     * @return Definition
     */
    public function resolve(RuleInterface $rule): Definition;
}
