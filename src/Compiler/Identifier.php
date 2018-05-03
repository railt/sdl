<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

/**
 * Class Identifier
 */
class Identifier
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array|string[]
     */
    private $namespace;

    /**
     * Identifier constructor.
     * @param string $name
     * @param array $namespace
     */
    public function __construct(string $name, array $namespace = [])
    {
        $this->name = $name;
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFullyQualifiedName(): string
    {
        return \implode('/', \array_merge($this->namespace, [$this->name]));
    }
}
