<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker\Common;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\SDL\Linker\Record\ProvidesName;

/**
 * Trait NameReaderTrait
 */
trait NameReaderTrait
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var bool|null
     */
    protected $global;

    /**
     * @param RuleInterface $rule
     * @return null|RuleInterface
     */
    protected function getTypeName(RuleInterface $rule): ?RuleInterface
    {
        return $rule->find('#TypeName', 0);
    }

    /**
     * @param RuleInterface $type
     * @return string
     */
    protected function readName(RuleInterface $type): string
    {
        $chunks = [];

        foreach ($type->getValue() as $chunk) {
            $chunks[] = $chunk;
        }

        return \implode(ProvidesName::NAMESPACE_SEPARATOR, $chunks);
    }

    /**
     * @param RuleInterface $type
     * @return bool
     */
    protected function readIsGlobalScope(RuleInterface $type): bool
    {
        return $type->getChild(0)->getName() === '#GlobalNamespace';
    }
}
