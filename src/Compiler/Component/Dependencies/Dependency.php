<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component\Dependencies;

use Railt\SDL\Compiler\Component\TypeName;
use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Class Dependency
 */
class Dependency
{
    /**
     * @var TypeName
     */
    private $name;

    /**
     * @var LocalContextInterface
     */
    private $context;

    /**
     * Dependency constructor.
     * @param TypeName $name
     * @param LocalContextInterface $context
     */
    public function __construct(TypeName $name, LocalContextInterface $context)
    {
        $this->name    = $name;
        $this->context = $context;
    }

    /**
     * @return TypeName
     */
    public function getName(): TypeName
    {
        return $this->name;
    }

    /**
     * @return LocalContextInterface
     */
    public function getContext(): LocalContextInterface
    {
        return $this->context;
    }
}
