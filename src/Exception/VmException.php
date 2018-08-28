<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Exception;

use Railt\SDL\Frontend\IR\Opcode;
use Railt\SDL\Frontend\IR\OpcodeInterface;

/**
 * Class VmException
 */
class VmException extends CompilerException
{
    /**
     * RuntimeException constructor.
     * @param Opcode|OpcodeInterface $opcode
     * @param string $message
     * @param mixed ...$args
     */
    public function __construct(OpcodeInterface $opcode, string $message, ...$args)
    {
        parent::__construct(\sprintf($message, ...$args), 0);

        if ($opcode instanceof Opcode) {
            $this->throwsIn($opcode->getFile(), $opcode->getOffset());
        }
    }
}
