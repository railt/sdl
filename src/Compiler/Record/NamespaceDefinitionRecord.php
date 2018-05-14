<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Record;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Common\NameReaderTrait;

/**
 * Class NamespaceDefinitionRecord
 */
class NamespaceDefinitionRecord extends BaseRecord implements ProvidesDefinitions, ProvidesContext, ProvidesPriority
{
    use NameReaderTrait;

    /**
     * @return string
     */
    public function getName(): string
    {
        if ($this->name === null) {
            /** @var RuleInterface|null $type */
            $type = $this->getTypeName($this->ast);

            $this->name = $type ? $this->readName($type) : '';
        }

        return $this->name;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return static::PRIORITY_DEFINITION;
    }

    /**
     * @return bool
     */
    public function atRoot(): bool
    {
        if ($this->global === null) {
            $atRoot = (bool) $this->ast->find('#GlobalNamespace');

            $this->global = $atRoot || $this->getName() === '';
        }

        return $this->global;
    }

    /**
     * @return bool
     */
    public function shouldRollback(): bool
    {
        return \count($this->getDefinitions()) > 0;
    }

    /**
     * @return bool
     */
    public function shouldRegister(): bool
    {
        return false;
    }

    /**
     * @return iterable
     */
    public function getDefinitions(): iterable
    {
        $rule = $this->ast->find('#ChildrenDefinitions');

        if ($rule === null) {
            return [];
        }

        return $rule->getChildren();
    }
}