<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Railt\Io\Readable;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Reflection;
use Railt\SDL\Backend\Generator;

/**
 * Class Backend
 */
class Backend implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Generator
     */
    private $generator;

    /**
     * Generator constructor.
     * @param Frontend $frontend
     * @param Reflection $reflection
     */
    public function __construct(Frontend $frontend, Reflection $reflection)
    {
        $this->generator = new Generator($reflection);
    }

    /**
     * @param Readable $file
     * @param iterable $records
     * @return Document
     */
    public function run(Readable $file, iterable $records): Document
    {
        return $this->generator->generate($file, $records);
    }
}
