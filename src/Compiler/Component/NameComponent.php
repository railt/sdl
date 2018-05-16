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
     * @var bool
     */
    private $unique = true;

    /**
     * NameComponent constructor.
     * @param LocalContextInterface $context
     * @param RuleInterface $ast
     */
    public function __construct(LocalContextInterface $context, RuleInterface $ast)
    {
        $name = \implode(ContextInterface::NAMESPACE_DELIMITER, \iterable_to_array($ast->getValue()));

        $this->name = $this->formatName($context, $name);
    }

    /**
     * @param bool|null $unique
     * @return bool
     */
    public function isUnique(bool $unique = null): bool
    {
        return $this->unique = ($unique ?? $this->unique);
    }

    /**
     * @param LocalContextInterface $context
     * @param string $name
     * @return string
     */
    private function formatName(LocalContextInterface $context, string $name): string
    {
        return $this->resolve($context, $this->escape($name));
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
     * @param string $name
     * @return string
     */
    private function escape(string $name): string
    {
        return \trim($name, ContextInterface::NAMESPACE_DELIMITER);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
