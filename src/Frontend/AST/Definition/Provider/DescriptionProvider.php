<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition\Provider;

use Railt\SDL\Frontend\Ast\Invocation\StringValue;

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
        if ($description = $this->first('Description', 1)) {
            /** @var StringValue $string */
            $string = $description->getChild(0);

            return $string->toPrimitive();
        }

        return null;
    }
}
