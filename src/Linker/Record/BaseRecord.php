<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker\Record;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\Io\Position;
use Railt\Io\Readable;
use Railt\SDL\Linker\Context\StackInterface;
use Railt\SDL\Stack\CallStack;

/**
 * Class BaseRecord
 */
abstract class BaseRecord implements RecordInterface
{
    /**
     * @var Readable
     */
    protected $file;

    /**
     * @var RuleInterface
     */
    protected $ast;

    /**
     * @var CallStack
     */
    protected $stack;

    /**
     * BaseRecord constructor.
     * @param Readable $file
     * @param RuleInterface $rule
     * @param CallStack $stack
     */
    public function __construct(Readable $file, RuleInterface $rule, CallStack $stack)
    {
        $this->file  = $file;
        $this->ast   = $rule;
        $this->stack = $stack;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->getFile()->getPosition($this->getAst()->getOffset());
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->file;
    }

    /**
     * @return RuleInterface
     */
    public function getAst(): RuleInterface
    {
        return $this->ast;
    }
}
