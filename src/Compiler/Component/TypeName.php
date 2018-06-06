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
use Railt\SDL\Compiler\Context\GlobalContextInterface;
use Railt\SDL\ECS\ComponentInterface;

/**
 * Class TypeName
 */
class TypeName implements \IteratorAggregate, ComponentInterface
{
    /**
     * Namespace delimiter
     */
    public const NAMESPACE_DELIMITER = '/';

    /**
     * @var iterable|string[]
     */
    private $chunks;

    /**
     * @var bool
     */
    private $global;

    /**
     * TypeName constructor.
     * @param iterable $chunks
     * @param bool $global
     */
    public function __construct(iterable $chunks, bool $global = false)
    {
        $this->chunks = $chunks;
        $this->global = $global;
    }

    /**
     * @param string $name
     * @param bool $global
     * @return TypeName
     */
    public static function fromString(string $name, bool $global = false): self
    {
        $chunks = \explode(self::NAMESPACE_DELIMITER, $name);

        return new static($chunks, $global);
    }

    /**
     * @param RuleInterface $rule
     * @return TypeName
     */
    public static function fromAst(RuleInterface $rule): self
    {
        \assert($rule->getName() === '#TypeName',
            'Internal Error: Bad name root node ' . (string)$rule);

        $chunks   = \iterable_to_array($rule->getValue());
        $isGlobal = (bool)$rule->find('#GlobalTypeNamespace', 0);

        return new static($chunks, $isGlobal);
    }

    /**
     * @param GlobalContextInterface $context
     * @return TypeName
     */
    public static function anonymous(GlobalContextInterface $context): self
    {
        if ($context->count()) {
            return clone $context->current()->getName();
        }

        return new static([], true);
    }

    /**
     * @return iterable
     */
    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->chunks);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return \implode(self::NAMESPACE_DELIMITER, $this->chunks);
    }

    /**
     * @param TypeName $prefix
     * @return TypeName
     */
    public function prepend(self $prefix): self
    {
        return $prefix->append($this);
    }

    /**
     * @param TypeName $suffix
     * @return TypeName
     */
    public function append(self $suffix): self
    {
        if ($suffix->isGlobal()) {
            return clone $suffix;
        }

        $chunks = \array_merge($this->chunks, \iterator_to_array($suffix));

        return new static($chunks, $this->isGlobal());
    }

    /**
     * @return bool
     */
    public function isGlobal(): bool
    {
        return $this->global;
    }
}
