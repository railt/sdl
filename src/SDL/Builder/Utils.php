<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Exception\SyntaxException;

/**
 * Class Utils
 */
class Utils
{
    /**
     * @param RuleInterface $rule
     * @return string|null
     */
    public static function findName(RuleInterface $rule): ?string
    {
        foreach ($rule->getChildren() as $child) {
            if ($child->getName() === 'Name') {
                return $child->getValue();
            }
        }

        return null;
    }

    /**
     * @param Readable $file
     * @param RuleInterface $rule
     * @return string
     * @throws SyntaxException
     */
    public static function getName(Readable $file, RuleInterface $rule): string
    {
        if (($name = static::findName($rule)) !== null) {
            return $name;
        }

        $exception = new SyntaxException('Unable to determine type name');
        $exception->throwsIn($file, $rule->getOffset());

        throw $exception;
    }

    /**
     * @param RuleInterface $rule
     * @return string|null
     */
    public static function findDescription(RuleInterface $rule): ?string
    {
        return static::leaf($rule, 'Description');
    }

    /**
     * @param RuleInterface $rule
     * @param string $name
     * @param int $group
     * @return string|null
     */
    public static function leaf(RuleInterface $rule, string $name, int $group = 0): ?string
    {
        if ($rule = static::rule($rule, $name)) {
            return $rule->getValue($group);
        }

        return null;
    }

    /**
     * @param RuleInterface $rule
     * @param string $name
     * @return RuleInterface|null
     */
    public static function rule(RuleInterface $rule, string $name): ?RuleInterface
    {
        foreach ($rule->getChildren() as $child) {
            if ($child->getName() === $name) {
                return $child;
            }
        }

        return null;
    }
}
