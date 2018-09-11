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
use Railt\SDL\Backend\Validator;
use Railt\SDL\IR\Definition;

/**
 * Class Generator
 */
class Backend implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Generator
     */
    private $generator;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * Generator constructor.
     * @param Reflection $reflection
     */
    public function __construct(Reflection $reflection)
    {
        $this->generator = new Generator($reflection);
        $this->validator = new Validator();
    }

    /**
     * @param Readable $file
     * @param iterable|Definition[] $ir
     * @return Document
     * @throws Exception\InternalException
     */
    public function run(Readable $file, iterable $ir): Document
    {
        return $this->generator->generate($file, $this->validate($ir));
    }

    /**
     * @param iterable|Definition[] $ir
     * @return iterable|Definition[]
     * @throws Exception\InternalException
     */
    private function validate(iterable $ir): iterable
    {
        foreach ($ir as $definition) {
            yield $this->validator->validate($definition);
        }
    }
}
