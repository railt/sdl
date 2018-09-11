<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR;

use Railt\Io\PositionInterface;
use Railt\Io\Readable;

/**
 * @property string $name
 * @property string $description
 * @property TypeInterface|Type $type
 * @property array|Definition[] $directives
 *
 * -- Interfaces and Objects:
 * @property array|Definition[] $implements
 * @property array|Definition[] $fields
 *
 * -- Fields, InputFields, Arguments and EnumValues:
 * @property mixed $hint
 * @property int $modifiers
 *
 * -- Fields and Directives:
 * @property array|Definition[] $arguments
 *
 * -- InputFields
 * @property mixed $default
 *
 * -- Enums
 * @property array|Definition[] $values
 *
 * -- EnumValues
 * @property mixed $value
 *
 * -- Unions and Documents
 * @property array|Definition[] $definitions
 *
 * -- Documents
 * @property array|Definition[] $extensions
 *
 * -- Scalars and Extensions
 * @property string $extends
 */
class Definition extends ValueObject implements DefinitionInterface
{
    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var Readable|null
     */
    private $file;

    /**
     * @var PositionInterface
     */
    private $position;

    /**
     * @var int
     */
    protected $skip = self::SKIP_NULL;

    /**
     * @param Readable $file
     * @param int $offset
     * @return Definition|$this
     */
    public function in(Readable $file, int $offset = 0): self
    {
        $this->file   = $file;
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->getPosition()->getLine();
    }

    /**
     * @return PositionInterface
     */
    private function getPosition(): PositionInterface
    {
        if ($this->position === null) {
            $this->position = $this->getFile()->getPosition($this->offset);
        }

        return $this->position;
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        \assert($this->file !== null);

        return $this->file;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->getPosition()->getColumn();
    }
}
