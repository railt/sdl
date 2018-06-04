<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Context;

use Railt\Io\Readable;
use Railt\SDL\Compiler\Common\TypeName;

/**
 * Class LocalContext
 */
class LocalContext extends Context implements LocalContextInterface
{
    public function create(string $name, Readable $file = null): LocalContextInterface
    {
        throw new \LogicException('The ' . __METHOD__ . ' not implemented yet');
    }

    public function previous(): ContextInterface
    {
        throw new \LogicException('The ' . __METHOD__ . ' not implemented yet');
    }

    public function getFile(): Readable
    {
        throw new \LogicException('The ' . __METHOD__ . ' not implemented yet');
    }

    public function getName(): TypeName
    {
        throw new \LogicException('The ' . __METHOD__ . ' not implemented yet');
    }
}
