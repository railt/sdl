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
use Railt\Reflection\Contracts\Reflection;
use Railt\Reflection\Document;
use Railt\SDL\Frontend\Context\LocalContext;
use Railt\SDL\Frontend\Record\RecordInterface;

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
     * @param RecordInterface[]|iterable $records
     * @return Document
     */
    public function generate(Readable $file, iterable $records): Document
    {
        foreach ($records as $record) {
            echo __FILE__ . ':' . __LINE__ . "\n";
            echo \json_encode($record, \JSON_PRETTY_PRINT);
        }

        die;

        $document = new Document($this->reflection, $file);

        return $document;
    }
}
