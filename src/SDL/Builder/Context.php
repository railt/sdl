<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use Railt\Io\Readable;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Document;

/**
 * Class Context
 */
class Context
{
    /**
     * @var Readable
     */
    private $file;

    /**
     * @var Definition
     */
    private $definition;

    /**
     * Context constructor.
     * @param Readable $file
     * @param Definition $definition
     */
    public function __construct(Readable $file, Definition $definition)
    {
        $this->file = $file;
        $this->definition = $definition;
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->definition->getDocument();
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->file;
    }

    /**
     * @return Definition
     */
    public function getDefinition(): Definition
    {
        return $this->definition;
    }
}
