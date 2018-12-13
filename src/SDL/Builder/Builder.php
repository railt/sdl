<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\AbstractDefinition;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Compiler\Process;

/**
 * Class Builder
 */
abstract class Builder implements BuilderInterface
{
    /**
     * @var Readable
     */
    protected $file;

    /**
     * @var Definition
     */
    protected $definition;

    /**
     * @var Document
     */
    protected $document;

    /**
     * @var RuleInterface
     */
    protected $ast;

    /**
     * @var Process
     */
    private $process;

    /**
     * Builder constructor.
     * @param Context $ctx
     * @param RuleInterface $ast
     * @param Process $process
     */
    public function __construct(Context $ctx, RuleInterface $ast, Process $process)
    {
        $this->ast = $ast;
        $this->process = $process;
        $this->file = $ctx->getFile();
        $this->definition = $ctx->getDefinition();
        $this->document = $this->definition->getDocument();
    }

    /**
     * @param \Closure $then
     */
    public function async(\Closure $then): void
    {
        $this->process->async($then);
    }

    /**
     * @param Definition|AbstractDefinition $definition
     * @return Definition
     */
    protected function bind(Definition $definition): Definition
    {
        $definition->withOffset($this->ast->getOffset());

        return $definition;
    }

    /**
     * @return string
     * @throws \Railt\SDL\Exception\SyntaxException
     */
    protected function getName(): string
    {
        return Utils::getName($this->file, $this->ast);
    }

    /**
     * @return string|null
     */
    protected function findName(): ?string
    {
        return Utils::findName($this->ast);
    }
}
