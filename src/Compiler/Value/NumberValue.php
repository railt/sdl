<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Value;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\RuleInterface;

/**
 * Class StringValue
 */
class StringValue extends Value
{
    /**
     * @return string
     */
    protected static function getAstName(): string
    {
        return 'String';
    }

    /**
     * @param RuleInterface $rule
     * @return mixed|string
     */
    protected function parse(RuleInterface $rule): string
    {
        $value = $this->unpackStringData($rule->getChild(0));

        return $value;
    }

    /**
     * @param LeafInterface $ast
     * @return string
     */
    private function unpackStringData(LeafInterface $ast): string
    {
        return $ast->getValue(1);
    }
}
