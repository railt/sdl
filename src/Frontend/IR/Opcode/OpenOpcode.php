<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR\Opcode;

use Railt\Io\Readable;
use Railt\SDL\Frontend\IR\Opcode;

/**
 * Creates a new document from the selected file.
 */
class OpenOpcode extends Opcode
{
    /**
     * Open constructor.
     * @param Readable $file
     */
    public function __construct(Readable $file)
    {
        parent::__construct(self::RL_OPEN, $file);
    }
}
