<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language;

use Railt\Io\Readable;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Compiler;
use Railt\Tests\SDL\TestCase;

/**
 * Class LanguageTestCase
 */
abstract class LanguageTestCase extends TestCase
{
    /**
     * @return Readable
     */
    abstract public function sources(): Readable;

    /**
     * @return Document
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function document(): Document
    {
        $compiler = new Compiler();

        return $compiler->compile($this->sources());
    }
}
