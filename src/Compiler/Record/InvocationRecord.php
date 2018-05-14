<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Record;

/**
 * Class InvocationRecord
 */
class InvocationRecord extends BaseRecord implements ProvidesPriority
{
    /**
     * @return int
     */
    public function getPriority(): int
    {
        return static::PRIORITY_INVOCATION;
    }
}
