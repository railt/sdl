<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component;

use Railt\SDL\Compiler\Context\ContextInterface;

/**
 * Class ContextComponent
 */
class ContextComponent implements ComponentInterface
{
    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * ContextComponent constructor.
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * @return ContextInterface
     */
    public function getContext(): ContextInterface
    {
        return $this->context;
    }
}
