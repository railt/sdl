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
use Railt\Io\Position;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Context\LocalContextInterface;
use Railt\SDL\Compiler\Support\AstFinder;
use Railt\SDL\ECS\Entity;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class Record
 */
abstract class Record extends Entity implements RecordInterface
{
    use AstFinder;

    public const DEFAULT     = 0x01;
    public const INVOCATION  = 0x02;
    public const EXTENSION   = 0x03;
    public const DEFINITION  = 0x04;
    public const INSTRUCTION = 0x05;

    /**
     * @var RuleInterface
     */
    private $ast;

    /**
     * @var LocalContextInterface
     */
    private $context;

    /**
     * @var Position
     */
    private $position;

    /**
     * Record constructor.
     * @param LocalContextInterface $context
     * @param RuleInterface $ast
     */
    public function __construct(LocalContextInterface $context, RuleInterface $ast)
    {
        $this->ast      = $ast;
        $this->context  = $context;
        $this->position = $context->getFile()->getPosition($ast->getOffset());
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return static::DEFAULT;
    }

    /**
     * @return RuleInterface
     */
    public function getAst(): RuleInterface
    {
        return $this->ast;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->position->getLine();
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->position->getColumn();
    }

    /**
     * @return iterable
     */
    public function getDependencies(): iterable
    {
        return [];
    }

    /**
     * @return CallStackInterface
     */
    protected function getCallStack(): CallStackInterface
    {
        return $this->getContext()->getCallStack();
    }

    /**
     * @return LocalContextInterface
     */
    public function getContext(): LocalContextInterface
    {
        return $this->context->current();
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->context->getFile();
    }
}
