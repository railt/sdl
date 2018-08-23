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
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\TypeInterface;
use Railt\SDL\Frontend\AST\Value\ValueInterface;

/**
 * Class LocalContext
 */
class LocalContext extends AbstractContext implements LocalContextInterface
{
    /**
     * @var Definition
     */
    protected $context;

    /**
     * @var ContextInterface|LocalContext|DocumentContext
     */
    private $parent;

    /**
     * @var array|Definition[]|TypeInterface[]
     */
    private $variables;

    /**
     * LocalContext constructor.
     * @param LocalContextInterface $parent
     * @param TypeDefinition $definition
     * @param array|TypeDefinition[]|TypeInterface[] $variables
     */
    public function __construct(LocalContextInterface $parent, TypeDefinition $definition, array $variables = [])
    {
        $this->parent = $parent;
        $this->context = $definition;
        $this->variables = $variables;
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->parent->getFile();
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->parent->getDocument();
    }

    /**
     * @return ContextInterface|LocalContextInterface
     */
    public function getParent(): ContextInterface
    {
        return $this->parent;
    }

    /**
     * @return TypeDefinition
     */
    public function getDefinition(): TypeDefinition
    {
        return $this->context;
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
     * Create new type definition with variables.
     *
     * @param array|ValueInterface[] $variables
     * @return TypeDefinition
     */
    public function make(array $variables = []): TypeDefinition
    {
        // TODO Add comparation $this->variables (list of TypeDefinition[] or Type[]) with $variables (list or ValueInterface[])

        return $this->context;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->context;
    }
}
