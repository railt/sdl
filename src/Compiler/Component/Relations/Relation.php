<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component\Relations;

use Railt\Io\Position;
use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Class Relation
 */
class Relation
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Position
     */
    private $position;

    /**
     * Relation constructor.
     * @param string $name
     * @param Position $position
     */
    public function __construct(string $name, Position $position)
    {
        $this->name = $name;
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->position;
    }
}
