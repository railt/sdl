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
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Document as DocumentInterface;
use Railt\Reflection\Definition\Dependent\DirectiveLocation;
use Railt\Reflection\Definition\DirectiveDefinition;
use Railt\Reflection\Document;
use Railt\SDL\Compiler\Pipeline;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class DirectiveDelegate
 */
class DirectiveDelegate extends TypeDefinitionDelegate
{
    /**
     * @param DocumentInterface|Document $document
     * @return Definition
     */
    protected function create(DocumentInterface $document): Definition
    {
        return new DirectiveDefinition($document, $this->getTypeName());
    }

    /**
     * @return void
     */
    protected function register(): void
    {
        $this->future(Pipeline::PRIORITY_DEFINITION, function() {
            $this->bootLocations($this->definition);
        });
    }

    /**
     * @param DirectiveDefinition|TypeDefinition $directive
     */
    private function bootLocations(DirectiveDefinition $directive): void
    {
        foreach ($this->getLocations($directive) as $offset => $location) {
            $this->transaction($location, function (DirectiveLocation $location) use ($directive): void {
                $this->verifyLocation($location);
                $this->verifyDuplication($directive, $location);

                $directive->withLocation($location);
            });
        }
    }

    /**
     * @param DirectiveDefinition $directive
     * @param DirectiveLocation $location
     * @throws \Railt\SDL\Exception\CompilerException
     */
    private function verifyDuplication(DirectiveDefinition $directive, DirectiveLocation $location): void
    {
        if ($directive->hasLocation($location->getName())) {
            $error = 'Could not determine the location %s, because %s already exists';
            $error = \sprintf($error, $location, $directive->getLocation($location->getName()));

            throw (new TypeConflictException($error))->using($this->getCallStack())->in($location);
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
