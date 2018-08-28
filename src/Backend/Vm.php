<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Backend;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Railt\Io\Readable;
use Railt\Reflection\Contracts\Document as DocumentInterface;
use Railt\Reflection\Contracts\Reflection;
use Railt\Reflection\Document;
use Railt\SDL\Exception\VmException;
use Railt\SDL\Frontend\IR\OpcodeInterface;

/**
 * Class Vm
 */
class Vm implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Reflection
     */
    private $root;

    /**
     * Process constructor.
     * @param Reflection $root
     */
    public function __construct(Reflection $root)
    {
        $this->root = $root;
    }

    /**
     * @param Readable $file
     * @param iterable $opcodes
     * @return Document|DocumentInterface
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function run(Readable $file, iterable $opcodes): Document
    {
        foreach ($opcodes as $opcode) {
            //$opcode->exec();
        }
        die(42);

        if ($result instanceof DocumentInterface) {
            return $result;
        }

        $message = 'Zero VM Stack index contains a non-compatible with %s value';
        throw new VmException($opcode, $message, DocumentInterface::class);
    }

    /**
     * @param OpcodeInterface $opcode
     * @param mixed $result
     */
    private function log(OpcodeInterface $opcode, $result): void
    {
        $value = \gettype($result);

        if (\is_object($result)) {
            $value = \get_class($result) . '#' . \spl_object_hash($result);

            if (\method_exists($result, '__toString')) {
                $value = (string)$result;
            }
        }

        $message = \sprintf('%4s = %s', '#' . $opcode->getId(), $value);

        $this->logger->debug($message);
    }
}
