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
use Railt\SDL\Frontend\IR\JoinedOpcode;

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
        $runtime = new Runtime($this->root, $file);

        /**
         * @var JoinedOpcode $opcode
         * @var mixed $result
         */
        foreach ($runtime->execute($opcodes) as $opcode => $result) {
            if ($this->logger) {
                $value = \get_class($result) . '#' . \spl_object_hash($result);
                $message = \sprintf('%4s = %s', '#' . $opcode->getId(), $value);

                $this->logger->debug($message);
            }
        }

        $result = $runtime->get(0);

        if ($result instanceof DocumentInterface) {
            return $result;
        }

        throw new VmException($opcode, 'Zero VM Stack index contains a non-compatible with %s value',
            DocumentInterface::class);
    }
}
