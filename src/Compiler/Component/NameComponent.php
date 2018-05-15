<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Context\ContextInterface;
use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Class NameComponent
 */
class NameComponent implements ComponentInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * NameComponent constructor.
     * @param LocalContextInterface $context
     * @param string $name
     * @param bool $global
     */
    public function __construct(LocalContextInterface $context, string $name, bool $global = false)
    {
        $this->name = $this->formatName($context, $name, $global);
    }

    /**
     * @param LocalContextInterface $context
     * @param string $name
     * @param bool $global
     * @return string
     */
    private function formatName(LocalContextInterface $context, string $name, bool $global): string
    {
        $name = $this->escape($name);

        return $global ? $name : $this->resolve($context, $name);
    }

    /**
     * @param string $name
     * @return string
     */
    private function escape(string $name): string
    {
        return \trim($name, ContextInterface::NAMESPACE_DELIMITER);
    }

    /**
     * @param LocalContextInterface $context
     * @param string $name
     * @return string
     */
    private function resolve(LocalContextInterface $context, string $name): string
    {
        $name = \implode(ContextInterface::NAMESPACE_DELIMITER, [$context->getName(), $name]);

        return $this->escape($name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param LocalContextInterface $context
     * @param RuleInterface $rule
     * @return NameComponent
     */
    public static function fromAst(LocalContextInterface $context, RuleInterface $rule): NameComponent
    {
        $isGlobal = $rule->getChild(0)->getName() === '#GlobalNamespace';

        $name = \implode(ContextInterface::NAMESPACE_DELIMITER, \iterable_to_array($rule->getValue()));

        return new static($context, $name, $isGlobal);
    }
}
