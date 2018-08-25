<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Backend;

use Railt\Io\Readable;
use Railt\Reflection\Contracts\Reflection;
use Railt\SDL\Backend\State\OpenState;
use Railt\SDL\Backend\State\StateInterface;
use Railt\SDL\Exception\VmException;
use Railt\SDL\Frontend\IR\JoinedOpcode;
use Railt\SDL\Frontend\IR\OpcodeInterface;

/**
 * Class Runtime
 */
class Runtime
{
    /**
     * @var string[]|StateInterface[]
     */
    private const RESOLVERS = [
        OpcodeInterface::RL_OPEN => OpenState::class,
    ];

    /**
     * @var array
     */
    private $result = [];

    /**
     * @var Readable
     */
    private $input;

    /**
     * @var Reflection
     */
    private $root;

    /**
     * Runtime constructor.
     * @param Reflection $root
     * @param Readable $input
     */
    public function __construct(Reflection $root, Readable $input)
    {
        $this->input = $input;
        $this->root = $root;
    }

    /**
     * @return Readable
     */
    public function getInput(): Readable
    {
        return $this->input;
    }

    /**
     * @param int $id
     * @return mixed|null
     */
    public function get(int $id)
    {
        return $this->result[$id] ?? null;
    }

    /**
     * @return Reflection
     */
    public function getReflection(): Reflection
    {
        return $this->root;
    }

    /**
     * @param iterable|OpcodeInterface[]|JoinedOpcode[] $opcodes
     * @return iterable
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function execute(iterable $opcodes): iterable
    {
        foreach ($opcodes as $opcode) {
            yield $opcode => $this->result[$opcode->getId()] = $this->getState($opcode)->resolve($opcode);
        }
    }

    /**
     * @param OpcodeInterface|JoinedOpcode $opcode
     * @return StateInterface
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function getState(OpcodeInterface $opcode): StateInterface
    {
        $state = self::RESOLVERS[$opcode->getOperation()] ?? null;

        if ($state === null) {
            $error = 'Unrecognized opcode (#%d) %s';
            throw new VmException($opcode, $error, $opcode->getId(), $opcode->getName());
        }

        return new $state($this);
    }
}
