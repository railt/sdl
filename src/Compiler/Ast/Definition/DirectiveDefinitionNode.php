<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Definition;

use Railt\Parser\Ast\LeafInterface;
use Railt\SDL\Compiler\Ast\Dependent\ArgumentDefinitionNode;

/**
 * Class DirectiveDefinitionNode
 */
class DirectiveDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return iterable|string[]
     */
    public function getLocations(): iterable
    {
        $locations = $this->first('DirectiveLocations', 1);

        /** @var LeafInterface $location */
        foreach ($locations as $location) {
            yield $location => $location->getValue();
        }
    }

    /**
     * @return iterable|ArgumentDefinitionNode[]
     */
    public function getArguments(): iterable
    {
        $arguments = $this->first('DirectiveArguments', 1);

        if ($arguments) {
            foreach ($arguments as $argument) {
                yield $argument;
            }
        }
    }
}
