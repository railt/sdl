<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\AST\Parser;
use Railt\Io\Exception\ExternalFileException;
use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\ParserInterface;
use Railt\SDL\Exception\InternalErrorException;
use Railt\SDL\Exception\SyntaxException;

/**
 * Class Frontend
 */
class Frontend
{
    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * Frontend constructor.
     */
    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * @param Readable $schema
     * @return RuleInterface
     * @throws SyntaxException
     * @throws InternalErrorException
     */
    public function exec(Readable $schema): RuleInterface
    {
        try {
            return $this->parser->parse($schema);
        } catch (ExternalFileException $e) {
            $exception = new SyntaxException($e->getMessage());
            $exception->throwsIn($schema, $e->getLine(), $e->getColumn());

            throw $exception;
        } catch (\Throwable $e) {
            throw new InternalErrorException($e->getMessage());
        }
    }
}
