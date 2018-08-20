<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System\Provider;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Invocation\DirectiveInvocation;
use Railt\SDL\Compiler\System\System;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class DocumentSystem
 */
class DocumentSystem extends System
{
    /**
     * @param Definition|\Railt\Reflection\Document $document
     * @param RuleInterface $ast
     */
    public function resolve(Definition $document, RuleInterface $ast): void
    {
        if ($document instanceof Document) {
            foreach ($ast as $child) {
                $this->deferred(function () use ($document, $child) {
                    $definition = $this->process->build($child, $document);

                    if ($definition instanceof TypeDefinition) {
                        $this->deferred(function () use ($definition, $document) {
                            if ($document->getDictionary()->has($definition->getName())) {
                                throw $this->redeclareException($definition);
                            }

                            $document->withDefinition($definition);
                        });
                    }


                    if ($definition instanceof DirectiveInvocation) {
                        $this->complete(function () use ($definition, $document) {
                            $document->withDirective($definition);
                        });
                    }
                });
            }
        }
    }
}
