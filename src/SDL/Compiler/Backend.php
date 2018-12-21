<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition as DefinitionInterface;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Builder\BuilderInterface;
use Railt\SDL\Builder\Context;
use Railt\SDL\Builder\Definition;
use Railt\SDL\Builder\Invocation;
use Railt\SDL\Exception\TypeException;

/**
 * Class Backend
 */
class Backend
{
    /**
     * @var Process
     */
    private $process;

    /**
     * Backend constructor.
     * @param Process $process
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }
}
