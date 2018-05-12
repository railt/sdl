<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker\Record;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\SDL\Linker\Common\NameReaderTrait;

/**
 * Class DefinitionRecord
 */
class DefinitionRecord extends BaseRecord implements ProvidesPriority, ProvidesName, ProvidesContext
{
    use NameReaderTrait;

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return static::PRIORITY_DEFINITION;
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function getName(): string
    {
        if ($this->name === null) {
            /** @var RuleInterface|null $type */
            $type = $this->getTypeName($this->ast);

            if ($type === null) {
                throw new \RuntimeException('Type name missing which must provides by the ' . \get_class($this));
            }

            $this->name = $this->readName($type);
        }

        return $this->name;
    }

    /**
     * @return bool
     */
    public function atRoot(): bool
    {
        if ($this->global === null) {
            /** @var RuleInterface|null $type */
            $type = $this->getTypeName($this->ast);

            $this->global = $type ? $this->readIsGlobalScope($type) : false;
        }

        return $this->global;
    }

    /**
     * @return bool
     */
    public function shouldRollback(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function shouldRegister(): bool
    {
        return true;
    }
}
