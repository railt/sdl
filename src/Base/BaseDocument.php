<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Base;

use Railt\Io\Readable;
use Railt\SDL\Dictionary;
use Railt\SDL\Base\Invocation\Directive\DirectivesContainer;
use Railt\SDL\Reflection\Definition\TypeDefinition;
use Railt\SDL\Reflection\Document;

/**
 * Class BaseDocument
 */
class BaseDocument extends BaseDefinition implements Document
{
    use DirectivesContainer;

    /**
     * @var array|string[]
     */
    protected $types = [];

    /**
     * BaseDocument constructor.
     * @param Dictionary $dictionary
     * @param Readable $file
     */
    public function __construct(Dictionary $dictionary, Readable $file)
    {
        parent::__construct($this, $dictionary, $file);
    }

    /**
     * @param TypeDefinition $type
     */
    public function addTypeDefinition(TypeDefinition $type): void
    {
        $this->types[] = $type->getName();
        $this->dictionary->register($type);
    }

    /**
     * @return iterable
     */
    public function getTypeDefinitions(): iterable
    {
        foreach ($this->types as $type) {
            yield $this->dictionary->get($type);
        }
    }

    /**
     * @param string $name
     * @return null|TypeDefinition
     */
    public function getTypeDefinition(string $name): ?TypeDefinition
    {
        if ($this->hasTypeDefinition($name) && $this->dictionary->has($name)) {
            return $this->dictionary->get($name);
        }

        return null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasTypeDefinition(string $name): bool
    {
        return \in_array($name, $this->types, true) &&
            $this->dictionary->has($name);
    }

    /**
     * @return int
     */
    public function getNumberOfTypeDefinition(): int
    {
        return \count($this->types);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'Document<' . $this->file->getPathname() . '>';
    }
}
