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
 * Class EnumExtensionBuilder
 */
class EnumExtensionBuilder extends ExtensionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface $ast
     * @return \Generator|mixed|Definition
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $extension = new Definition();
        $extension->type = Type::of(Type::ENUM_EXTENSION);
        $extension->extends = yield $ast->first('EnumDefinition', 1);

        return $extension;
    }
}
