<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast;

/**
 * Class TypeName
 */
class TypeName extends Identifier
{
    /**
     * @var array|Identifier[]
     */
    public array $arguments = [];

    /**
     * Name constructor.
     *
     * @param Identifier $name
     * @param array|Identifier[] $arguments
     */
    public function __construct(Identifier $name, array $arguments = [])
    {
        parent::__construct($name->value);

        $this->arguments = $arguments;
    }

    /**
     * @return bool
     */
    public function isGeneric(): bool
    {
        return $this->arguments !== [];
    }

    /**
     * @param array|Identifier[] $children
     * @return Identifier
     */
    public static function create($children): Identifier
    {
        return new static(\array_shift($children), $children);
    }
}
