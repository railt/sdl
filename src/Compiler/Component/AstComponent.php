<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component;

use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Compiler\Parser\Ast\RuleInterface;

/**
 * Class AstComponent
 */
class AstComponent implements ComponentInterface
{
    /**
     * @var RuleInterface
     */
    private $ast;

    /**
     * AstComponent constructor.
     * @param NodeInterface $ast
     */
    public function __construct(NodeInterface $ast)
    {
        \assert($ast instanceof RuleInterface);

        $this->ast = $ast;
    }

    /**
     * @return RuleInterface
     */
    public function getAst(): RuleInterface
    {
        return $this->ast;
    }
}
