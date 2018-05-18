<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component;

/**
 * Class TypeComponent
 */
class TypeComponent implements ComponentInterface
{
    public const TYPE_NAMESPACE = 'Namespace';
    public const TYPE_OBJECT = 'Object';
    public const TYPE_INTERFACE = 'Interface';
    public const TYPE_INPUT = 'Input';
    public const TYPE_SCALAR = 'Scalar';
    public const TYPE_ENUM = 'Enum';
    public const TYPE_DIRECTIVE = 'Directive';
    public const TYPE_SCHEMA = 'Schema';
    public const TYPE_UNION = 'Union';

    /**
     * @var string
     */
    private $type;

    /**
     * TypeComponent constructor.
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
