<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Definition;

use Railt\Parser\Ast\LeafInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Document as DocumentInterface;
use Railt\Reflection\Definition\Dependent\DirectiveLocation;
use Railt\Reflection\Definition\DirectiveDefinition;
use Railt\Reflection\Document;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class DirectiveDelegate
 */
class DirectiveDelegate extends DefinitionDelegate
{
    /**
     * @param DocumentInterface|Document $document
     * @return Definition
     */
    protected function bootDefinition(DocumentInterface $document): Definition
    {
        return new DirectiveDefinition($document, $this->getTypeName());
    }

    /**
     * @param Definition|DirectiveDefinition $definition
     */
    protected function before(Definition $definition): void
    {
        $this->bootLocations($definition);
    }

    /**
     * @param DirectiveDefinition $directive
     */
    private function bootLocations(DirectiveDefinition $directive): void
    {
        foreach ($this->getLocations($directive) as $offset => $location) {
            $this->transaction($location, function (DirectiveLocation $location) use ($directive): void {
                // TODO Add duplication verification
                $directive->withLocation($location);
                $this->verifyLocation($location);
            });
        }
    }

    /**
     * @param DirectiveDefinition $definition
     * @return iterable|DirectiveLocation[]
     */
    private function getLocations(DirectiveDefinition $definition): iterable
    {
        /** @var LeafInterface $child */
        foreach ($this->first('DirectiveLocations', 1) as $child) {
            yield (new DirectiveLocation($definition, $child->getValue()))->withOffset($child->getOffset());
        }
    }

    /**
     * @param DirectiveLocation $location
     * @throws \Railt\SDL\Exception\CompilerException
     */
    private function verifyLocation(DirectiveLocation $location): void
    {
        $isValid = $location->isExecutable() || $location->isPrivate();

        if (! $isValid) {
            $error = \sprintf('Invalid directive location %s', $location);

            throw $this->error(new TypeConflictException($error))->in($location);
        }
    }
}
