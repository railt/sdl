<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Railt\Io\Readable;
use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Exception\InternalException;
use Railt\SDL\Frontend\Builder\BuilderInterface;
use Railt\SDL\Frontend\Interceptor\InterceptorInterface;
use Railt\SDL\Frontend\Interceptor\RuleInterceptor;

/**
 * Class Builder
 */
class Builder
{
    /**
     * @var string[]|BuilderInterface[]
     */
    private const DEFAULT_BUILDER_DEFINITIONS = [

    ];

    /**
     * string[]|InterceptorInterface[]
     */
    private const DEFAULT_INTERCEPTORS = [
        RuleInterceptor::class,
    ];

    /**
     * @var array|BuilderInterface[]
     */
    private $builders = [];

    /**
     * @var SymbolTable
     */
    private $table;

    /**
     * @var array|InterceptorInterface[]
     */
    private $interceptors = [];

    /**
     * Builder constructor.
     */
    public function __construct()
    {
        $this->table = new SymbolTable();
        $this->bootDefaults();
    }

    /**
     * @return void
     */
    private function bootDefaults(): void
    {
        $this->bootDefaultBuilders();
        $this->bootDefaultInterceptors();
    }

    /**
     * @return void
     */
    private function bootDefaultBuilders(): void
    {
        foreach (self::DEFAULT_BUILDER_DEFINITIONS as $rule => $builder) {
            $this->builders[$rule] = new $builder($this, $this->table);
        }
    }

    /**
     * @return void
     */
    private function bootDefaultInterceptors(): void
    {
        foreach (self::DEFAULT_INTERCEPTORS as $interceptor) {
            $this->interceptors[] = new $interceptor($this, $this->table);
        }
    }

    /**
     * @param Readable $file
     * @param RuleInterface $ast
     * @return ValueObject
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(Readable $file, RuleInterface $ast): ValueObject
    {
        $document = new ValueObject();
        $document->definitions = [];

        foreach ($ast->getChildren() as $child) {
            $document->definitions[] = $this->reduce($file, $child);
        }

        return $document;
    }

    /**
     * @param Readable $file
     * @param RuleInterface $ast
     * @return mixed
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function reduce(Readable $file, RuleInterface $ast)
    {
        $process = $this->get($file, $ast)->reduce($ast);

        if ($process instanceof \Generator) {
            return $this->run($file, $process);
        }

        return $process;
    }

    /**
     * @param Readable $file
     * @param \Generator $process
     * @return mixed
     */
    private function run(Readable $file, \Generator $process)
    {
        while ($process->valid()) {
            $value = $process->current();

            foreach ($this->interceptors as $interceptor) {
                if ($interceptor->match($value)) {
                    $value = $interceptor->apply($file, $value);
                    break;
                }
            }

            $process->send($value);
        }

        return $process->getReturn();
    }

    /**
     * @param Readable $file
     * @param RuleInterface $ast
     * @return BuilderInterface
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function get(Readable $file, RuleInterface $ast): BuilderInterface
    {
        $builder = $this->builders[$ast->getName()] ?? null;

        if ($builder === null) {
            $error = 'Unrecognized AST rule %s';
            throw (new InternalException(\sprintf($error, $ast->getName())))->throwsIn($file, $ast->getOffset());
        }

        return $builder;
    }
}
