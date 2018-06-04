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
 * Class GlobalContext
 */
class GlobalContext extends Context implements GlobalContextInterface
{
    /**
     * @var array|LocalContextInterface[]
     */
    private $pool = [];

    /**
     * @var \SplStack|LocalContextInterface[]
     */
    private $stack;

    /**
     * GlobalContext constructor.
     */
    public function __construct()
    {
        $this->stack = new \SplStack();
    }

    public function create(string $name, Readable $file = null): LocalContextInterface
    {
        if ($file === null) {
            throw new \InvalidArgumentException('Could not create a new context from global without file');
        }
    }
}
