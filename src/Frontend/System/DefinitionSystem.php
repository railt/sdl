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
use Railt\SDL\Frontend\AST\ProvidesName;
use Railt\SDL\Frontend\AST\ProvidesType;
use Railt\SDL\Frontend\IR\Opcode;
use Railt\SDL\Frontend\IR\Prototype;

/**
 * Class DefinitionSystem
 */
class DefinitionSystem implements SystemInterface
{
    /**
     * @param RuleInterface $ast
     * @return bool
     */
    public function match(RuleInterface $ast): bool
    {
        return $ast instanceof ProvidesType;
    }

    /**
     * @param Readable $file
     * @param RuleInterface|ProvidesType|ProvidesName $ast
     * @return \Generator|mixed
     */
    public function apply(Readable $file, RuleInterface $ast)
    {
        yield new Prototype($ast->getOffset(), Opcode::C_TYPE_DEFINITION, [
            $ast->getType(),
            $ast->getFullName()
        ]);
    }
}
