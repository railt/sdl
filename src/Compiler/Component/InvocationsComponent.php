<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Component\InvocationsComponent\Invocation;
use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Class InvocationsComponent
 */
class InvocationsComponent implements ComponentInterface
{
    /**
     * @var array
     */
    private $invocations;

    /**
     * InvocationsComponent constructor.
     * @param iterable|Invocation[] $invocations
     */
    public function __construct(iterable $invocations = [])
    {
        $this->invocations = \iterable_to_array($invocations);
    }

    /**
     * @param Invocation $invocation
     */
    public function addInvocation(Invocation $invocation): void
    {
        $this->invocations[] = $invocation;
    }

    /**
     * @return iterable|Invocation[]
     */
    public function getInvocations(): iterable
    {
        return $this->invocations;
    }

    /**
     * @param LocalContextInterface $context
     * @param RuleInterface $ast
     * @return iterable|Invocation[]
     */
    public static function interfaces(LocalContextInterface $context, RuleInterface $ast): iterable
    {
        $children = $ast->find('#Implements', 0);
        $children = $children ? $children->getChildren() : [];

        foreach ($children as $child) {
            yield new Invocation($context, NameComponent::fromAst($context, $child)->getName());
        }
    }
}
