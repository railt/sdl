<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Base\Definition;

use Railt\Io\Readable;
use Railt\SDL\Base\BaseDefinition;
use Railt\SDL\Base\Invocation\Directive\DirectivesContainer;
use Railt\SDL\Dictionary;
use Railt\SDL\Reflection\Definition\TypeDefinition;
use Railt\SDL\Reflection\Document;

/**
 * Class BaseTypeDefinition
 */
abstract class BaseTypeDefinition extends BaseDefinition implements TypeDefinition
{
    use DirectivesContainer;

    /**
     * @var string
     */
    protected $fqn;

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string|null
     */
    protected $deprecation;

    /**
     * BaseTypeDefinition constructor.
     * @param string $fqn
     * @param Document $document
     * @param Dictionary $dictionary
     * @param Readable $file
     */
    public function __construct(string $fqn, Document $document, Dictionary $dictionary, Readable $file)
    {
        $this->fqn = $fqn;

        parent::__construct($document, $dictionary, $file);
    }

    /**
     * @return string
     */
    public function getShortName(): string
    {
        return \array_last(\explode('/', $this->fqn));
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return $this->deprecation !== null;
    }

    /**
     * @param null|string $deprecation
     */
    public function setDeprecationReason(?string $deprecation): void
    {
        $this->deprecation = $deprecation;
    }

    /**
     * @return string
     */
    public function getDeprecationReason(): string
    {
        return (string)$this->deprecation;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getType()->getName() . '<' . $this->getName() . '>';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->fqn;
    }
}
