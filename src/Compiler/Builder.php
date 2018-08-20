<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition as DefinitionInterface;
use Railt\Reflection\Contracts\Reflection;
use Railt\Reflection\Document;
use Railt\SDL\Ast\ProvidesDefinition;
use Railt\SDL\Compiler\Process\Emittable;
use Railt\SDL\Compiler\Process\Pipeline;
use Railt\SDL\Exception\CompilerException;
use Railt\SDL\Exception\InternalException;

/**
 * Class Factory
 */
class Builder implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var SystemManager
     */
    private $systems;

    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * Process constructor.
     */
    public function __construct()
    {
        $this->pipeline = new Pipeline();
        $this->systems = new SystemManager($this, $this->pipeline);
    }

    /**
     * @param Reflection $root
     * @param Readable $file
     * @param RuleInterface $ast
     * @return Document
     */
    public function run(Reflection $root, Readable $file, RuleInterface $ast): Document
    {
        $document = new Document($root, $file);

        if ($this->logger) {
            $this->logger->debug(\sprintf('Create document %s', $document));
        }

        $this->systems->resolve($document, $ast);

        /** @var Emittable $deferred */
        foreach ($this->pipeline as $deferred) {
            $handler = $deferred->emit();

            while ($handler->valid()) {
                $handler->next();
            }
        }

        if ($this->logger) {
            $this->logger->debug(\sprintf('Complete document %s', $document));
        }

        return $document;
    }

    /**
     * @param RuleInterface $rule
     * @param DefinitionInterface $parent
     * @return DefinitionInterface
     */
    public function build(RuleInterface $rule, DefinitionInterface $parent): DefinitionInterface
    {
        return $this->buildDefinition($rule, $parent);
    }

    /**
     * @param ProvidesDefinition|RuleInterface $rule
     * @param DefinitionInterface $parent
     * @return DefinitionInterface
     * @throws CompilerException
     */
    private function buildDefinition(RuleInterface $rule, DefinitionInterface $parent): DefinitionInterface
    {
        if (! $rule instanceof ProvidesDefinition) {
            $error = \vsprintf('%s AST should implement %s interface', [
                $rule->getName(), ProvidesDefinition::class]);

            throw new InternalException($error);
        }

        $definition = $rule->resolve($parent);

        if ($this->logger) {
            $this->logger->debug(\sprintf('Create type definition %s from %s', $definition, $parent));
        }

        $this->systems->resolve($definition, $rule);

        return $definition;
    }

    /**
     * Sets a logger instance on the object.
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;

        $this->pipeline->setLogger($logger);
        $this->systems->setLogger($logger);
    }
}
