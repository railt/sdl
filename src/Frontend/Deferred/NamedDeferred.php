<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Deferred;

use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Definition\DefinitionInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class NamedDeferred
 */
class NamedDeferred extends Deferred implements Identifiable
{
    /**
     * @var TypeNameInterface
     */
    private $name;

    /**
     * NamedDeferred constructor.
     * @param DefinitionInterface $def
     * @param ContextInterface $ctx
     * @param \Closure|null $then
     */
    public function __construct(DefinitionInterface $def, ContextInterface $ctx, \Closure $then = null)
    {
        $this->name = $def;

        parent::__construct($ctx, $then);
    }

    /**
     * @return DefinitionInterface
     */
    public function getDefinition(): DefinitionInterface
    {
        return $this->name;
    }
}
