<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker\Record;

/**
 * Interface ProvidesName
 */
interface ProvidesName
{
    /**
     * @var string
     */
    public const PRIVATE_NAME_PREFIX = ':';

    /**
     * @var string
     */
    public const NAMESPACE_SEPARATOR = '/';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     */
    public function rename(string $name): void;

    /**
     * @return bool
     */
    public function atRoot(): bool;

    /**
     * @return bool
     */
    public function shouldRegister(): bool;
}
