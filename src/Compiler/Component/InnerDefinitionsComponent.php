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

/**
 * Class InnerDefinitionsComponent
 */
class InnerDefinitionsComponent implements ComponentInterface
{
    /**
     * @var iterable
     */
    private $definitions;

    /**
     * InnerDefinitionsComponent constructor.
     * @param iterable $definitions
     */
    public function __construct(iterable $definitions)
    {
        $this->definitions = $definitions;
    }

    /**
     * @param RuleInterface $rule
     */
    public function add(RuleInterface $rule): void
    {
        $this->definitions[] = $rule;
    }

    /**
     * @return iterable|RuleInterface[]
     */
    public function getDefinitions(): iterable
    {
        return $this->definitions;
    }
}
