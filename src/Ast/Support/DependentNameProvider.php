<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Support;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\RuleInterface;

/**
 * Trait DependentNameProvider
 */
trait DependentNameProvider
{
    /**
     * @return null|string
     */
    public function getFullName(): ?string
    {
        $name = $this->getNameNode();

        if ($name instanceof RuleInterface) {
            /** @var LeafInterface $value */
            $value = $name->getChild(0);

            return $value->getValue();
        }

        return null;
    }

    /**
     * @return null|RuleInterface
     */
    protected function getNameNode(): ?RuleInterface
    {
        return $this->first('DependentName', 1);
    }
}
