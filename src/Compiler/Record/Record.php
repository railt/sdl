<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Record;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Component\AstComponent;
use Railt\SDL\Compiler\Component\ContextComponent;
use Railt\SDL\Compiler\Component\PositionComponent;
use Railt\SDL\Compiler\Context\ContextInterface;

/**
 * Class Record
 */
abstract class Record implements RecordInterface
{
    use HasComponents;

    /**
     * Record constructor.
     * @param ContextInterface $context
     * @param RuleInterface $ast
     */
    public function __construct(ContextInterface $context, RuleInterface $ast)
    {
        $this->boot($context, $ast);
    }

    /**
     * @param ContextInterface $context
     * @param RuleInterface $ast
     */
    private function boot(ContextInterface $context, RuleInterface $ast): void
    {
        $this->add(
            new AstComponent($ast),
            new ContextComponent($context),
            new PositionComponent($context->getFile(), $ast->getOffset())
        );
    }
}
