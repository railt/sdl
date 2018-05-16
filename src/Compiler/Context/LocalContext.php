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
use Railt\SDL\Compiler\Context\GlobalContextInterface as Pool;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class LocalContext
 */
class LocalContext extends Context implements LocalContextInterface
{
    /**
     * @var Readable
     */
    private $file;

    /**
     * @var Pool
     */
    private $global;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var ContextInterface|LocalContextInterface|GlobalContextInterface
     */
    private $previous;

    /**
     * @var bool
     */
    private $public = true;

    /**
     * LocalContext constructor.
     * @param CallStackInterface $stack
     * @param Readable $file
     * @param Pool $pool
     * @param string $name
     */
    public function __construct(CallStackInterface $stack, Readable $file, Pool $pool, string $name = null)
    {
        $this->file = $file;
        $this->name = $name;

        $this->global   = $pool;
        $this->previous = $pool->current();

        parent::__construct($stack);
    }

    /**
     * @param bool|null $public
     * @return bool
     */
    public function isPublic(bool $public = null): bool
    {
        return $this->public = $public ?? $this->public;
    }

    /**
     * @return ContextInterface
     */
    public function current(): ContextInterface
    {
        return $this->global->current();
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        // Name can be an empty
        return (string)$this->name;
    }

    /**
     * @return ContextInterface|LocalContextInterface|GlobalContextInterface
     */
    public function previous(): ContextInterface
    {
        return $this->previous;
    }

    /**
     * @return GlobalContextInterface
     */
    public function global(): GlobalContextInterface
    {
        return $this->global;
    }

    /**
     * @return bool
     */
    public function atRoot(): bool
    {
        return (bool)$this->name;
    }

    /**
     * @param string|null $name
     * @param Readable|null $file
     * @return LocalContextInterface
     */
    public function create(string $name = null, Readable $file = null): LocalContextInterface
    {
        return $this->global->create($name, $file);
    }
}
