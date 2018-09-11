<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Extension;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\Builder\ExtensionBuilder;
use Railt\SDL\IR\Definition;
use Railt\SDL\IR\Type;

/**
 * Class ScalarExtensionBuilder
 */
class ScalarExtensionBuilder extends ExtensionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface $ast
     * @return \Generator|mixed|Definition
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $extension = new Definition();
        $extension->type = Type::of(Type::SCALAR_EXTENSION);
        $extension->extends = yield $ast->first('ScalarDefinition', 1);

        return $extension;
    }
}
