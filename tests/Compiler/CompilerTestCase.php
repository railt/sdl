<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Tests\Compiler;

use Railt\SDL\Tests\TestCase;

/**
 * Class CompilerTestCase
 */
abstract class CompilerTestCase extends TestCase
{
    /**
     * @return array
     */
    public function typesDataProvider(): array
    {
        return [
            'Scalar'      => ['scalar'],
            'Object'      => ['type'],
            'Interface'   => ['interface'],
            'InputObject' => ['input'],
            'Enum'        => ['enum'],
            'Union'       => ['union'],
        ];
    }
}
