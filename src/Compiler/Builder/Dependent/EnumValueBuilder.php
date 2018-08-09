<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Builder\Dependent;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Type as TypeInterface;
use Railt\Reflection\Definition\Dependent\EnumValueDefinition;
use Railt\Reflection\Definition\EnumDefinition;
use Railt\Reflection\Document;
use Railt\Reflection\Type;
use Railt\SDL\Compiler\Ast\Dependent\EnumValueDefinitionNode;
use Railt\SDL\Compiler\Ast\TypeHintNode;
use Railt\SDL\Compiler\Builder\Builder;
use Railt\SDL\Compiler\Builder\Virtual\TypeHint;

/**
 * Class EnumValueBuilder
 */
class EnumValueBuilder extends Builder
{
    /**
     * @param RuleInterface|EnumValueDefinitionNode $rule
     * @param Definition|EnumDefinition $parent
     * @return Definition
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $value = new EnumValueDefinition($parent, $rule->getValueName());
        $value->withOffset($rule->getOffset());
        $value->withDescription($rule->getDescription());

        $this->when->runtime(function () use ($rule, $value): void {
            if ($hint = $rule->getTypeHint()) {
                $virtualTypeHint = $this->virtualTypeHint($value, $hint);

                $value->withValue($this->valueOf($virtualTypeHint, $rule->getValue()));

                $this->when->resolving(function() use ($value, $virtualTypeHint) {
                    $this->shouldBeTypeOf($value, $virtualTypeHint->getDefinition(), [
                        Type::SCALAR,
                        Type::ENUM,
                        Type::INPUT_OBJECT,
                        Type::ANY,
                    ]);
                });
            }

            foreach ($rule->getDirectives() as $ast) {
                $value->withDirective($this->dependent($ast, $value));
            }
        });

        return $value;
    }

    /**
     * @param EnumValueDefinition $value
     * @param TypeHintNode $ast
     * @return TypeHint
     */
    private function virtualTypeHint(EnumValueDefinition $value, TypeHintNode $ast): TypeHint
    {
        $virtual = new class($value->getDocument(), $value->getName()) extends TypeHint {
            /**
             * @var string
             */
            private $name;

            /**
             * @param Document $document
             * @param string $name
             */
            public function __construct(Document $document, string $name)
            {
                $this->name = $name;
                parent::__construct($document);
            }

            /**
             * @return string
             */
            public function getName(): string
            {
                return $this->name;
            }

            /**
             * @return TypeInterface
             */
            public static function getType(): TypeInterface
            {
                return Type::of(Type::ENUM_VALUE);
            }
        };

        $virtual->withOffset($ast->getOffset());
        $virtual->withTypeDefinition($ast->getTypeName());
        $virtual->withModifiers($ast->getModifiers());

        return $virtual;
    }
}
