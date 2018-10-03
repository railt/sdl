<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\Exception\UnexpectedTokenException;
use Railt\Parser\Exception\UnrecognizedTokenException;
use Railt\SDL\Exception\SyntaxException;
use Railt\SDL\Frontend\Builder;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Context\GlobalContext;
use Railt\SDL\Frontend\Parser;
use Railt\SDL\IR\DefinitionValueObject;
use Railt\SDL\IR\SymbolTable;

/**
 * Class Frontend
 */
class Frontend implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * Frontend constructor.
     */
    public function __construct()
    {
        $this->builder = new Builder();
    }

    /**
     * @param Readable $file
     * @return iterable
     * @throws SyntaxException
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function load(Readable $file)
    {
        return $this->buildFile($file);
    }

    /**
     * @param Readable $readable
     * @return iterable
     * @throws SyntaxException
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function buildFile(Readable $readable)
    {
        $process = $this->builder->buildFile($readable);

        /**
         * @var ContextInterface $context
         * @var mixed $result
         */
        foreach ($process as [$context, $result]) {
            if ($this->filter($context, $result)) {
                yield $result;
            }
        }
    }

    /**
     * @param ContextInterface $ctx
     * @param mixed $result
     * @return bool
     */
    private function filter(ContextInterface $ctx, $result): bool
    {
        return $result !== null;
    }

    /**
     * @param LoggerInterface $logger
     * @return Frontend
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }
}
