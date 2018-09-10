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
use Railt\SDL\IR\Definition;

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

        $document->definitions = [];
        foreach ($ast->getChildren() as $child) {
            $document->definitions[] = yield $child;
        }

        return $document;
    }
}
