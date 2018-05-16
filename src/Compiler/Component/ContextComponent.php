<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component;

use Railt\Io\Readable;
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
    private $rollback = true;

    /**
     * ContextComponent constructor.
     * @param LocalContextInterface $current
     * @param string $name
     * @param Readable|null $file
     */
    public function __construct(LocalContextInterface $current, string $name, Readable $file = null)
    {
        $this->parent = $current;
        $this->current = $current->create($name, $file);
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
     * @param bool|null $rollback
     * @return bool
     */
    public function shouldRollback(bool $rollback = null): bool
    {
        return $this->rollback = $rollback ?? $this->rollback;
    }

    /**
     * @param bool|null $public
     * @return bool
     */
    public function isPublic(bool $public = null): bool
    {
        return $this->current->isPublic($public);
    }
}
