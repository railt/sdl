<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;

/**
 * Interface SystemInterface
 */
interface SystemInterface
{
    /**
     * Priority for primary pending code execution.
     * @var int
     */
    public const PRIORITY_DEFERRED = 0x00;

    /**
     * Priority for linking all type definitions at this point
     * should already be loaded successfully.
     * @var int
     */
    public const PRIORITY_LINKING = 0x10;

    /**
     * Priority for modifying existing structures and type definitions.
     * @var int
     */
    public const PRIORITY_EXTENSION = 0x20;

    /**
     * Priority for matching, and type inference.
     * @var int
     */
    public const PRIORITY_INFERENCE = 0x30;

    /**
     * Compile types with values, inference for types for values.
     * @var int
     */
    public const PRIORITY_RUNTIME = 0x40;

    /**
     * The final construction of types and measures of descendants with parents.
     * @var int
     */
    public const PRIORITY_COMPLETE = 0x50;

    /**
     * @param Definition $definition
     * @param RuleInterface $ast
     */
    public function resolve(Definition $definition, RuleInterface $ast): void;
}
