<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component;

/**
 * Class VisibilityComponent
 */
class VisibilityComponent
{
    /**
     * @var bool
     */
    private $public;

    /**
     * VisibilityComponent constructor.
     * @param bool $public
     */
    public function __construct(bool $public)
    {
        $this->public = $public;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public;
    }
}
