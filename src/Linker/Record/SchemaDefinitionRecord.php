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

/**
 * Class SchemaDefinitionRecord
 */
class SchemaDefinitionRecord extends DefinitionRecord
{
    /**
     * @var string
     */
    public const DEFAULT_SCHEMA_NAME = ProvidesName::PRIVATE_NAME_PREFIX . 'Schema';

    /**
     * @return string
     */
    public function getName(): string
    {
        if ($this->name === null) {
            /** @var RuleInterface|null $type */
            $type = $this->getTypeName($this->ast);

            $this->name = $type ? $this->readName($type) : static::DEFAULT_SCHEMA_NAME;
        }

        return $this->name;
    }
}
