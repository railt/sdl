<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Backend;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Railt\Io\Readable;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Reflection;

/**
 * Class Generator
 */
class Generator implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Reflection
     */
    private $reflection;

    /**
     * Generator constructor.
     * @param Reflection $reflection
     */
    public function __construct(Reflection $reflection)
    {
        $this->reflection = $reflection;
    }

    /**
     * @param Readable $file
     * @param iterable $ir
     * @return Document
     */
    public function run(Readable $file, iterable $ir): Document
    {

    }
}
