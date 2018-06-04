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

/**
 * Interface ContextInterface
 */
interface ContextInterface
{
    /**
     * @var string
     */
    public const NAMESPACE_DELIMITER = '/';

    /**
     * @param string $name
     * @param Readable|null $file
     * @return LocalContextInterface
     */
    public function create(string $name, Readable $file = null): LocalContextInterface;
}
