<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Common;

use Railt\Parser\Ast\RuleInterface;

/**
 * Trait DescriptionProvider
 */
trait DescriptionProvider
{
    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        /** @var RuleInterface $description */
        $description = $this->first('Description', 1);

        return $description ? $description->getChild(0)->toPrimitive() : null;
    }
}
