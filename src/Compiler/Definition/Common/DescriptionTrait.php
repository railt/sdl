<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Definition\Common;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\AbstractTypeDefinition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\SDL\Compiler\Value\StringValue;

/**
 * Trait DescriptionTrait
 */
trait DescriptionTrait
{
    /**
     * @param TypeDefinition|AbstractTypeDefinition $type
     * @param RuleInterface $rule
     */
    protected function withDescription(TypeDefinition $type, RuleInterface $rule): void
    {
        /** @var RuleInterface $description */
        $description = $rule->first('Description', 1);

        if ($description) {
            $type->withDescription($this->parseString($description));
        }
    }

    /**
     * @param RuleInterface $rule
     * @return string
     */
    private function parseString(RuleInterface $rule): string
    {
        $value = new StringValue($rule->getChild(0));

        return $value->toScalar();
    }
}
