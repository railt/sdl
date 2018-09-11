<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Exception\InternalException;
use Railt\SDL\IR\Definition;
use Railt\SDL\IR\Type;

/**
 * Class DocumentBuilder
 */
class DocumentBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface $ast
     * @return \Generator|mixed|Definition|\Railt\SDL\IR\TypeDefinition|\Railt\SDL\IR\TypeInvocation
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $document = new Definition();
        $document->in($file);

        $document->type = Type::DOCUMENT;

        $document->definitions =
        $document->extensions  =
        $document->directives  = [];

        foreach ($ast->getChildren() as $child) {
            if ($result = yield $child) {
                $this->append($document, $result);
            }
        }

        return $document;
    }

    /**
     * @param Definition $document
     * @param Definition $value
     * @return void
     */
    private function append(Definition $document, Definition $value): void
    {
        switch (true) {
            case $this->isTypeDefinition($value):
                $document->definitions[] = $value;
                break;

            case $this->isTypeExtension($value):
                $document->extensions[] = $value;
                break;

            case $this->isDirective($value):
                $document->directives[] = $value;
                break;

            default:
                throw new InternalException(
                    'Unrecognized struct ' . $value->toJson(\JSON_PRETTY_PRINT)
                );
        }
    }

    /**
     * @param Definition $definition
     * @return bool
     */
    private function isTypeDefinition(Definition $definition): bool
    {
        return $definition->type && $definition->type->isIndependent();
    }

    /**
     * @param Definition $definition
     * @return bool
     */
    private function isTypeExtension(Definition $definition): bool
    {
        return $definition->type && $definition->type->isExtension();
    }

    /**
     * @param Definition $definition
     * @return bool
     */
    private function isDirective(Definition $definition): bool
    {
        return $definition->type === null;
    }
}
