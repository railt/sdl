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
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class Context
 */
class Context implements ContextInterface
{
    /**
     * Contains delimiter character for namespace.
     */
    public const NAMESPACE_DELIMITER = '/';

    /**
     * @var string
     */
    private $name;

    /**
     * @var Readable
     */
    private $file;

    /**
     * @var CallStackInterface
     */
    private $stack;

    /**
     * @var Pool
     */
    private $pool;

    /**
     * Context constructor.
     * @param string $name
     * @param Readable $file
     * @param CallStackInterface $stack
     * @param Pool $pool
     */
    public function __construct(Readable $file, CallStackInterface $stack, Pool $pool, string $name = '')
    {
        $this->name  = $this->escape($name);
        $this->file  = $file;
        $this->stack = $stack;
        $this->pool  = $pool;
    }

    /**
     * @param string $name
     * @return string
     */
    private function escape(string $name): string
    {
        return \trim($name, static::NAMESPACE_DELIMITER);
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->file;
    }

    /**
     * @return CallStackInterface
     */
    public function getStack(): CallStackInterface
    {
        return $this->stack;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ContextInterface
     */
    public function resolve(string $name): ContextInterface
    {
        $context = new self($this->resolveName($name), $this->file, $this->stack, $this->pool);

        $this->pool->push($context);

        return $context;
    }

    /**
     * @param string $name
     * @return string
     */
    private function resolveName(string $name): string
    {
        $name = $this->escape($name);

        if ($this->isGlobal()) {
            return $name;
        }

        if ($this->likeGlobal($name)) {
            return $this->name;
        }

        return \implode(static::NAMESPACE_DELIMITER, [$this->name, $name]);
    }

    /**
     * @return bool
     */
    public function isGlobal(): bool
    {
        return $this->likeGlobal($this->name);
    }

    /**
     * @param string $name
     * @return bool
     */
    private function likeGlobal(string $name): bool
    {
        return $name === '' || $name === self::NAMESPACE_DELIMITER;
    }

    /**
     * @return null|ContextInterface
     */
    public function previous(): ?ContextInterface
    {
        try {
            return $this->pool->pop();
        } catch (\OutOfBoundsException | \OutOfRangeException $e) {
            return null;
        }
    }
}
