<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Base;

use Railt\Io\Position;
use Railt\Io\Readable;
use Railt\SDL\Dictionary;
use Railt\SDL\Reflection\Definition;
use Railt\SDL\Reflection\Document;

/**
 * Class BaseDefinition
 */
abstract class BaseDefinition implements Definition
{
    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var Readable
     */
    protected $file;

    /**
     * @var Document
     */
    protected $document;

    /**
     * @var Dictionary
     */
    protected $dictionary;

    /**
     * BaseDefinition constructor.
     * @param Document $document
     * @param Dictionary $dictionary
     * @param Readable $file
     */
    public function __construct(Document $document, Dictionary $dictionary, Readable $file)
    {
        $this->file       = $file;
        $this->document   = $document;
        $this->dictionary = $dictionary;
    }

    /**
     * @param int $offset
     */
    protected function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @return Position
     */
    public function getDeclarationInfo(): Position
    {
        return $this->file->getPosition($this->offset);
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->file;
    }
}
