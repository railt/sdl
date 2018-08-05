<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language\Basics;

use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Tests\SDL\Language\LanguageTestCase;

/**
 * Class ObjectTestCase
 */
class ObjectTestCase extends LanguageTestCase
{
    /**
     * @return Readable
     */
    public function sources(): Readable
    {
        return File::fromSources('
            interface I {}
            type Example implements I {} 
        ');
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testExisting(): void
    {
        $this->assertNotNull($this->document()->getDefinition('Example'));
    }
}
