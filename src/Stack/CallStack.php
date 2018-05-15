<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Stack;

use Illuminate\Support\Str;
use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\Io\Position;
use Railt\Io\Readable;
use Railt\SDL\Exception\LossOfStackException;

/**
 * Class CallStack
 */
class CallStack implements CallStackInterface
{
    /**
     * @var \SplStack
     */
    private $stack;

    /**
     * CallStack constructor.
     */
    public function __construct()
    {
        $this->stack = new \SplStack();
    }

    /**
     * @param Readable $file
     * @param Position $position
     * @param string $message
     * @return CallStackInterface
     */
    public function push(Readable $file, Position $position, string $message): CallStackInterface
    {
        $this->stack->push(new Item($file, $position, $message));

        return $this;
    }

    /**
     * @param Readable $file
     * @param RuleInterface $ast
     * @return CallStackInterface
     */
    public function pushAst(Readable $file, RuleInterface $ast): CallStackInterface
    {
        $message = $this->astToMessage($ast);

        return $this->push($file, $file->getPosition($ast->getOffset()), $message);
    }

    /**
     * @param \Closure $then
     * @return CallStackInterface
     */
    public function then(\Closure $then): CallStackInterface
    {
        $then();

        $this->pop();

        return $this;
    }

    /**
     * @param RuleInterface $ast
     * @return string
     */
    private function astToMessage(RuleInterface $ast): string
    {
        $valueToString = function () use ($ast): string {
            $values = [];

            /** @var NodeInterface $node */
            foreach ($ast->getValue() as $node => $value) {
                $line = \str_replace(["\n", "\r"], '', \trim($value));
                $line = \preg_replace('/\s+/', ' ', $line);

                $values[] = $node->getName() . '(' . $line . ')';
            }

            return \implode(', ', $values);
        };

        return \vsprintf('%s->ast(%s)', [
            \trim($ast->getName(), '#'),
            Str::limit($valueToString(), 60),
        ]);
    }

    /**
     * @return Item
     */
    public function pop(): Item
    {
        if ($this->stack->count() === 0) {
            throw new LossOfStackException('Stack data lost during transaction closing', $this);
        }

        return $this->stack->pop();
    }

    /**
     * @return iterable|Item[]
     */
    public function getIterator(): iterable
    {
        yield from $this->stack;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->stack->count();
    }
}
