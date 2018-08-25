<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Backend\State;

use Railt\Io\Readable;
use Railt\Reflection\Document;
use Railt\SDL\Exception\VmException;
use Railt\SDL\Frontend\IR\JoinedOpcode;
use Railt\SDL\Frontend\IR\OpcodeInterface;

/**
 * Class OpenState
 */
class OpenState extends State
{
    /**
     * @param OpcodeInterface|JoinedOpcode $opcode
     * @return mixed|Document
     * @throws VmException
     */
    protected function execute(OpcodeInterface $opcode)
    {
        /** @var Readable $file */
        $file = $opcode->getOperand(0);

        if (! $file instanceof Readable) {
            $error = 'First operand of (#%d) %s should be instance of %s';
            throw new VmException($opcode, $error, $opcode->getId(), $opcode->getName(), Readable::class);
        }

        return new Document($this->getReflection(), $file);
    }
}
