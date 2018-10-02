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
use Railt\SDL\Frontend\Parser;
use Railt\SDL\IR\SymbolTable;
use Railt\SDL\IR\SymbolTableInterface;

/**
 * Class Frontend
 */
class Frontend implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var SymbolTableInterface
     */
    private $table;

    /**
     * Frontend constructor.
     */
    public function __construct()
    {
        $this->table   = new SymbolTable();
        $this->parser  = new Parser();
        $this->builder = new Builder($this, $this->table);
    }

    /**
     * @param Readable $readable
     * @return iterable
     * @throws SyntaxException
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function load(Readable $readable)
    {
        return $this->builder->build($readable, $this->parse($readable));
    }

    /**
     * Parse the file using top-down parser and
     * return the Abstract Syntax Tree.
     *
     * @param Readable $file
     * @return RuleInterface
     * @throws SyntaxException
     */
    private function parse(Readable $file): RuleInterface
    {
        try {
            return $this->parser->parse($file);
        } catch (UnexpectedTokenException | UnrecognizedTokenException $e) {
            $error = new SyntaxException($e->getMessage(), $e->getCode());
            $error->throwsIn($file, $e->getLine(), $e->getColumn());

            throw $error;
        }
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
