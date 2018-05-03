<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Base\Invocation\Directive;

use Railt\SDL\Reflection\Invocation\Directive\HasDirectives;
use Railt\SDL\Reflection\Invocation\DirectiveInvocation;

/**
 * Trait DirectivesContainer
 * @mixin HasDirectives
 */
trait DirectivesContainer
{
    /**
     * @var array[]
     */
    protected $directives = [];

    /**
     * @param DirectiveInvocation $directive
     */
    public function addDirective(DirectiveInvocation $directive): void
    {
        $this->directives[] = [
            $directive->getTypeDefinition()->getName() => $directive,
        ];
    }

    /**
     * @param string|null $name
     * @return iterable|\Traversable|DirectiveInvocation[]
     */
    public function getDirectives(string $name = null): iterable
    {
        /** @var array|DirectiveInvocation[] $directives */
        foreach ($this->directives as $directives) {
            foreach ($directives as $haystack => $invocation) {
                if ($name === null || $haystack === $name) {
                    yield $invocation;
                }
            }
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool
    {
        return \count(\iterator_to_array($this->getDirectives($name))) > 0;
    }

    /**
     * @return int
     */
    public function getNumberOfDirectives(): int
    {
        return \count($this->directives);
    }
}
