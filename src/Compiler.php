<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\Io\Readable;
use Railt\SDL\Compiler\Component\NameComponent;
use Railt\SDL\Compiler\Pipeline;
use Railt\SDL\Stack\CallStack;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class Compiler
 */
class Compiler
{
    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * @var CallStackInterface
     */
    private $stack;

    /**
     * Compiler constructor.
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function __construct()
    {
        $this->stack    = new CallStack();
        $this->pipeline = new Pipeline($this->stack);
    }

    /**
     * @return CallStackInterface
     */
    public function getStack(): CallStackInterface
    {
        return $this->stack;
    }

    /**
     * @param Readable $file
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \RuntimeException
     */
    public function parse(Readable $file): void
    {
        $types = $this->pipeline->read($file);

        //foreach ($types->getRecords() as $record) {
        //    echo 'Record: ' . $record->getAst()->getName() . "\n";
        //}

        foreach ($types->getDefinitions() as $definition) {
            echo 'Definition: ' . $definition->get(NameComponent::class)->getName() . "\n";
        }
    }
}
