<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use Railt\Io\Readable;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Backend\Value\ValueInterface;

/**
 * Class DocumentContext
 */
class DocumentContext extends AbstractContext implements LocalContextInterface
{
    /**
     * @var Document
     */
    protected $context;

    /**
     * @var ContextInterface
     */
    private $parent;

    /**
     * LocalContext constructor.
     * @param ContextInterface $parent
     * @param Document $document
     */
    public function __construct(ContextInterface $parent, Document $document)
    {
        $this->parent = $parent;
        $this->context = $document;
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->context;
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->context->getFile();
    }

    /**
     * @return ContextInterface|GlobalContext
     */
    public function getParent(): ContextInterface
    {
        return $this->parent;
    }

    /**
     * @param string $type
     * @param array|ValueInterface[] $variables
     * @return TypeDefinition
     */
    public function get(string $type, array $variables = []): TypeDefinition
    {
        if ($this->has($type)) {
            return $this->types[$type]->make($variables);
        }

        return $this->parent->get($type, $variables);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->context;
    }
}
