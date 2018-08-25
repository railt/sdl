<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Backend\State;

use Railt\Reflection\Contracts\Reflection;
use Railt\SDL\Backend\Runtime;
use Railt\SDL\Frontend\IR\OpcodeInterface;

/**
 * Class State
 */
abstract class State implements StateInterface
{
    /**
     * @var mixed
     */
    private $result;

    /**
     * @var bool
     */
    private $resolved = false;

    /**
     * @var Runtime
     */
    private $runtime;

    /**
     * State constructor.
     * @param Runtime $runtime
     */
    public function __construct(Runtime $runtime)
    {
        $this->runtime = $runtime;
    }

    /**
     * @return Reflection
     */
    public function getReflection(): Reflection
    {
        return $this->runtime->getReflection();
    }

    /**
     * @param OpcodeInterface $opcode
     * @return mixed
     */
    abstract protected function execute(OpcodeInterface $opcode);

    /**
     * @param OpcodeInterface $opcode
     * @return mixed
     */
    final public function resolve(OpcodeInterface $opcode)
    {
        if ($this->resolved === false) {
            $this->result = $this->execute($opcode);
            $this->resolved = true;
        }

        return $this->result;
    }
}
