<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component;

use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Class ContextComponent
 */
class ContextComponent implements ComponentInterface
{
    /**
     * @var LocalContextInterface
     */
    private $parent;

    /**
     * @var LocalContextInterface
     */
    private $current;

    /**
     * @var bool
     */
    private $rollback;

    /**
     * ContextComponent constructor.
     * @param LocalContextInterface $parent
     * @param LocalContextInterface $current
     * @param bool $rollback
     */
    public function __construct(LocalContextInterface $parent, LocalContextInterface $current, bool $rollback)
    {
        $this->parent = $parent;
        $this->current = $current;
        $this->rollback = $rollback;
    }

    /**
     * @return LocalContextInterface
     */
    public function getParentContext(): LocalContextInterface
    {
        return $this->parent;
    }

    /**
     * @return LocalContextInterface
     */
    public function getContext(): LocalContextInterface
    {
        return $this->current;
    }

    /**
     * @return bool
     */
    public function shouldRollback(): bool
    {
        return $this->rollback;
    }
}
