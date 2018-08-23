<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\Exception\UnexpectedTokenException;
use Railt\Parser\Exception\UnrecognizedTokenException;
use Railt\SDL\Exception\SyntaxException;
use Railt\SDL\Frontend\IR\OpcodeInterface;

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
     * @var SystemManager
     */
    private $system;

    /**
     * Frontend constructor.
     */
    public function __construct()
    {
        $this->parser = new Parser();
        $this->system = new SystemManager();
    }

    /**
     * @return SystemManager
     */
    public function getSystemManager(): SystemManager
    {
        return $this->system;
    }

    /**
     * @param Readable $file
     * @return iterable|OpcodeInterface[]
     * @throws SyntaxException
     */
    public function load(Readable $file): iterable
    {
        foreach ($this->system->run($file, $this->parse($file)) as $opcode) {
            if ($this->logger) {
                $this->logger->debug($opcode);
            }

            yield $opcode;
        }
    }

    /**
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
}
