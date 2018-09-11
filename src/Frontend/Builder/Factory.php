<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Exception\InternalException;
use Railt\SDL\Frontend\Builder\Definition\ArgumentBuilder;
use Railt\SDL\Frontend\Builder\Definition\DirectiveBuilder;
use Railt\SDL\Frontend\Builder\Definition\EnumBuilder;
use Railt\SDL\Frontend\Builder\Definition\EnumValueBuilder;
use Railt\SDL\Frontend\Builder\Definition\FieldBuilder;
use Railt\SDL\Frontend\Builder\Definition\InputBuilder;
use Railt\SDL\Frontend\Builder\Definition\InputFieldBuilder;
use Railt\SDL\Frontend\Builder\Definition\InterfaceBuilder;
use Railt\SDL\Frontend\Builder\Definition\ObjectBuilder;
use Railt\SDL\Frontend\Builder\Definition\ScalarBuilder;
use Railt\SDL\Frontend\Builder\Definition\SchemaBuilder;
use Railt\SDL\Frontend\Builder\Definition\SchemaFieldBuilder;
use Railt\SDL\Frontend\Builder\Definition\UnionBuilder;
use Railt\SDL\Frontend\Builder\Extension\EnumExtensionBuilder;
use Railt\SDL\Frontend\Builder\Extension\InputExtensionBuilder;
use Railt\SDL\Frontend\Builder\Extension\InterfaceExtensionBuilder;
use Railt\SDL\Frontend\Builder\Extension\ObjectExtensionBuilder;
use Railt\SDL\Frontend\Builder\Extension\ScalarExtensionBuilder;
use Railt\SDL\Frontend\Builder\Extension\SchemaExtensionBuilder;
use Railt\SDL\Frontend\Builder\Extension\UnionExtensionBuilder;
use Railt\SDL\Frontend\Builder\Invocation\DirectiveInvocationBuilder;
use Railt\SDL\Frontend\Builder\Invocation\ValueBuilder;

/**
 * Class Factory
 */
class Factory
{
    /**
     * @var string[]
     */
    private const AST_MAPPINGS = [
        // Root
        'Document'              => DocumentBuilder::class,
        // Type definitions
        'DirectiveDefinition'   => DirectiveBuilder::class,
        'EnumDefinition'        => EnumBuilder::class,
        'InputDefinition'       => InputBuilder::class,
        'InterfaceDefinition'   => InterfaceBuilder::class,
        'ObjectDefinition'      => ObjectBuilder::class,
        'ScalarDefinition'      => ScalarBuilder::class,
        'SchemaDefinition'      => SchemaBuilder::class,
        'UnionDefinition'       => UnionBuilder::class,
        // Dependent definitions
        'EnumValueDefinition'   => EnumValueBuilder::class,
        'ArgumentDefinition'    => ArgumentBuilder::class,
        'FieldDefinition'       => FieldBuilder::class,
        'InputFieldDefinition'  => InputFieldBuilder::class,
        'SchemaFieldDefinition' => SchemaFieldBuilder::class,
        // Invocations
        'DirectiveInvocation'   => DirectiveInvocationBuilder::class,
        'ConstantValue'         => ValueBuilder::class,
        'BooleanValue'          => ValueBuilder::class,
        'NumberValue'           => ValueBuilder::class,
        'StringValue'           => ValueBuilder::class,
        'NullValue'             => ValueBuilder::class,
        'InputValue'            => ValueBuilder::class,
        'ListValue'             => ValueBuilder::class,
        'ArgumentValue'         => ValueBuilder::class,
        // Extensions
        'EnumExtension'         => EnumExtensionBuilder::class,
        'InputExtension'        => InputExtensionBuilder::class,
        'InterfaceExtension'    => InterfaceExtensionBuilder::class,
        'ObjectExtension'       => ObjectExtensionBuilder::class,
        'ScalarExtension'       => ScalarExtensionBuilder::class,
        'SchemaExtension'       => SchemaExtensionBuilder::class,
        'UnionExtension'        => UnionExtensionBuilder::class,
    ];

    /**
     * @param Readable $file
     * @param RuleInterface $ast
     * @return BuilderInterface
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function resolve(Readable $file, RuleInterface $ast): BuilderInterface
    {
        $builder = self::AST_MAPPINGS[$ast->getName()] ?? null;

        if ($builder === null) {
            $error = 'Unrecognized AST node "%s"';
            throw (new InternalException(\sprintf($error, $ast->getName())))->throwsIn($file, $ast->getOffset());
        }

        return new $builder();
    }
}
