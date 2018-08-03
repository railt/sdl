<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Definition;

use Railt\Parser\Ast\Delegate;
use Railt\Parser\Ast\Rule;
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\Environment;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Compiler\Ast\TypeNameNode;
use Railt\SDL\Compiler\Ast\Value\ValueInterface;

/**
 * Class TypeDefinitionNode
 */
abstract class TypeDefinitionNode extends Rule implements Delegate
{
    /**
     * @var Document
     */
    private $document;

    /**
     * @param Environment $env
     */
    public function boot(Environment $env): void
    {
        $this->document = $env->get(Document::class);
    }

    /**
     * @return Document
     */
    protected function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        /** @var RuleInterface $description */
        $description = $this->first('Description', 1);

        return $description ? $description->getChild(0)->toPrimitive() : null;
    }

    /**
     * @return null|string
     */
    public function getTypeName(): ?string
    {
        /** @var TypeNameNode $name */
        $name = $this->first('TypeName', 1);

        return $name ? $name->getTypeName() : null;
    }

    /**
     * @return TypeDefinition
     */
    abstract public function getTypeDefinition(): TypeDefinition;
}
