<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @throws \InvalidArgumentException
     */
    public function tearDown(): void
    {
        $files = (new Finder())->files()->in(__DIR__ . '/Helpers/temp');

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            @\unlink($file->getRealPath());
        }

        parent::tearDown();
    }
}
