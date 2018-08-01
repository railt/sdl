<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Definition;

use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\Environment;
use Railt\Reflection\AbstractTypeDefinition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Dictionary;
use Railt\Reflection\Document;
use Railt\SDL\Compiler\Pipeline;
use Railt\SDL\Compiler\Value\StringValue;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class TypeDefinitionDelegate
 */
abstract class TypeDefinitionDelegate extends DefinitionDelegate
{
    /**
     * @param Environment $env
     */
    public function boot(Environment $env): void
    {
        parent::boot($env);

        $this->transaction($this->definition, function () {
            $this->register();
        });

        $this->future(Pipeline::PRIORITY_DEFINITION, function () {
            $this->addDescription($this->definition, $this);
        });
    }

    /**
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeNotFoundException
     */
    private function register(): void
    {
        /** @var Document $document */
        $document = $this->definition->getDocument();

        $this->verifyDuplication($this->definition, $document->getDictionary());

        $document->withDefinition($this->definition);
    }


    /**
     * @param TypeDefinition $type
     * @param Dictionary $dict
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeNotFoundException
     */
    private function verifyDuplication(TypeDefinition $type, Dictionary $dict): void
    {
        if ($dict->has($type->getName())) {
            $prev  = $dict->get($type->getName(), $type);
            $error = 'Could not redeclare type %s by %s';
            $error = \sprintf($error, $prev, $type);

            throw $this->error(new TypeConflictException($error));
        }
    }

    /**
     * @param TypeDefinition|AbstractTypeDefinition $type
     * @param RuleInterface $rule
     */
    protected function addDescription(TypeDefinition $type, RuleInterface $rule): void
    {
        /** @var RuleInterface $description */
        $description = $rule->first('Description', 1);

        if ($description) {
            $type->withDescription($this->parseString($description));
        }
    }

    /**
     * @param RuleInterface $rule
     * @return string
     */
    private function parseString(RuleInterface $rule): string
    {
        $value = new StringValue($rule->getChild(0));

        return $value->toScalar();
    }
}
