<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Stack;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\Io\Position;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Component\RenderComponent;
use Railt\SDL\Compiler\Record\RecordInterface;
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
     * @param RuleInterface $ast
     * @return CallStackInterface
     */
    public function pushAst(Readable $file, RuleInterface $ast): CallStackInterface
    {
        $message = \sprintf('<%s>', \trim($ast->getName(), '#'));

        return $this->push($file, $file->getPosition($ast->getOffset()), $message);
    }

    /**
     * @param Readable $file
     * @param Position $position
     * @param string|callable $message
     * @return CallStackInterface
     */
    public function push(Readable $file, Position $position, $message): CallStackInterface
    {
        $this->stack->push(new Item($file, $position, $message));

        return $this;
    }

    /**
     * @param RecordInterface $record
     * @return CallStackInterface
     */
    public function pushRecord(RecordInterface $record): CallStackInterface
    {
        $file     = $record->getContext()->getFile();
        $position = $file->getPosition($record->getAst()->getOffset());

        $this->push($file, $position, function () use ($record) {
            return $record->has(RenderComponent::class)
                ? $record->get(RenderComponent::class)->toString()
                : \get_class($record);
        });

        return $this;
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
