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

/**
 * Class Context
 */
abstract class Context implements ContextInterface
{
    /**
     * @param Readable $file
     * @param \Closure $then
     * @return LocalContextInterface
     */
    public function transact(Readable $file, \Closure $then): LocalContextInterface
    {
        $context = $this->create($file);

        $then($context, $file);

        return $this->complete();
    }
}