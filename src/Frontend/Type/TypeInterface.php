<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Type;

/**
 * Interface TypeInterface
 */
interface TypeInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function is(TypeInterface $type): bool;

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function instanceOf(TypeInterface $type): bool;
}
