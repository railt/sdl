<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Dependency;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Class Argument
 */
class Argument
{
    /**
     * @var LocalContextInterface
     */
    private $context;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Argument constructor.
     * @param LocalContextInterface $context
     * @param $value
     */
    public function __construct(LocalContextInterface $context, $value)
    {
        $this->context = $context;
        $this->value = $value;
    }

    /**
     * @return LocalContextInterface
     */
    public function getContext(): LocalContextInterface
    {
        return $this->context;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param RuleInterface $rule
     * @param LocalContextInterface $context
     */
    public static function fromAst(RuleInterface $rule, LocalContextInterface $context)
    {
        dd($rule->getName());

        switch ($rule->getName()) {

        }
    }
}
