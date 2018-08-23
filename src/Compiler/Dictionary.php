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
use Railt\Io\Readable;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Dictionary\CallbackDictionary;
use Railt\SDL\Compiler;

/**
 * Class Dictionary
 */
class Dictionary extends CallbackDictionary implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * Dictionary constructor.
     * @param Compiler $compiler
     */
    public function __construct(Compiler $compiler)
    {
        $this->compiler = $compiler;

        $this->addLoggerListener();
    }

    /**
     * @return void
     */
    private function addLoggerListener(): void
    {
        parent::onTypeNotFound(function (string $type, ?Definition $from): void {
            if ($this->logger) {
                $direct = 'Try to load type %s from direct method executing';
                $context = 'Try to load type %s from %s (%s:%d)';

                $message = $from === null ? \sprintf($direct, $type) : \sprintf($context, $type, $from,
                    $from->getFile(), $from->getLine());

                $this->logger->debug($message);
            }
        });
    }

    /**
     * @param \Closure $then
     */
    public function onTypeNotFound(\Closure $then): void
    {
        parent::onTypeNotFound(function (string $type, ?Definition $from) use ($then): void {
            if (($file = $then($type, $from)) instanceof Readable) {
                $this->compiler->compile($file);
            }
        });
    }
}
