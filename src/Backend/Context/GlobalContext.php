<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Exception\TypeNotFoundException;

/**
 * Class GlobalContext
 */
class GlobalContext extends AbstractContext
{
    /**
     * @param string $type
     * @param array $variables
     * @return TypeDefinition
     * @throws TypeNotFoundException
     */
    public function get(string $type, array $variables = []): TypeDefinition
    {
        if ($this->has($type)) {
            return $this->types[$type]->make($variables);
        }

        throw new TypeNotFoundException(\sprintf('Type %s not found or could not be loaded', $type));
    }

    /**
     * @param Document $document
     * @return DocumentContext
     */
    public function fromDocument(Document $document): DocumentContext
    {
        return new DocumentContext($this, $document);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return ':global';
    }
}
