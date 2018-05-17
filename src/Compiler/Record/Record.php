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
use Railt\SDL\Compiler\Component\RenderComponent;
use Railt\SDL\Compiler\Context\LocalContextInterface;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class Record
 */
abstract class Record implements RecordInterface
{
    use HasComponents;

    /**
     * @var LocalContextInterface
     */
    protected $context;

    /**
     * @var RuleInterface
     */
    protected $ast;

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
        $this->ast     = $ast;
        $this->context = $context;

        $this->add(new RenderComponent($this));
    }

    /**
     * @return LocalContextInterface
     */
    public function getContext(): LocalContextInterface
    {
        return $this->context;
    }

    /**
     * @return CallStackInterface
     */
    public function getStack(): CallStackInterface
    {
        return $this->context->getStack();
    }

    /**
     * @return RuleInterface
     */
    public function getAst(): RuleInterface
    {
        return $this->ast;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        if ($this->position === null) {
            $this->position = $this->getFile()->getPosition($this->ast->getOffset());
        }

        return $this->position;
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->context->getFile();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        try {
            return $this->get(RenderComponent::class)->toString() . ' in ' .
                $this->getFile()->getPathname() . ':' .
                $this->getPosition()->getLine();
        } catch (\Throwable $e) {
            return \get_class($this);
        }
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return static::DEFAULT;
    }
}
