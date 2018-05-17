<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component;

use Railt\SDL\Compiler\Record\RecordInterface;

/**
 * Class RenderComponent
 */
class RenderComponent implements ComponentInterface
{
    /**
     * @var RecordInterface
     */
    private $record;

    /**
     * @var string|null
     */
    private $message;

    /**
     * RenderComponent constructor.
     * @param RecordInterface $record
     */
    public function __construct(RecordInterface $record)
    {
        $this->record = $record;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        if ($this->message === null) {
            $this->message = \vsprintf('%s %s(%s) [%s]', [
                $this->getVisibility(),
                $this->getType(),
                $this->getName(),
                $this->getNamespace()
            ]);
        }

        return $this->message ??
            '<' . \trim($this->record->getAst()->getName(), '#') . '>';
    }

    /**
     * @return string
     */
    private function getName(): string
    {
        if ($this->record->has(NameComponent::class)) {
            return $this->record->get(NameComponent::class)->getName();
        }

        return ':undefined';
    }

    /**
     * @return string
     */
    private function getType(): string
    {
        if ($this->record->has(TypeComponent::class)) {
            return $this->record->get(TypeComponent::class)->getType();
        }

        return ':undefined';
    }

    /**
     * @return string
     */
    private function getNamespace(): string
    {
        return $this->record->getContext()->getName() ?: 'root';
    }

    /**
     * @return string
     */
    private function getVisibility(): string
    {
        $isPrivate = $this->record->has(VisibilityComponent::class) &&
            ! $this->record->get(VisibilityComponent::class)->isPublic();

        return $isPrivate ? 'inner' : 'public';
    }
}
