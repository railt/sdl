<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component\InvocationsComponent;

use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Class Invocation
 */
class Invocation
{
    /**
     * @var LocalContextInterface
     */
    private $context;

    /**
     * @var string
     */
    private $type;

    /**
     * Invocation constructor.
     * @param LocalContextInterface $context
     * @param string $type
     */
    public function __construct(LocalContextInterface $context, string $type)
    {
        $this->context = $context;
        $this->type = $type;
    }
}
