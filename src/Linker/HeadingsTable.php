<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker;

use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\Io\Readable;
use Railt\SDL\Exception\BadAstMappingException;
use Railt\SDL\Parser\Factory;

/**
 * Class HeadingsTable
 */
class HeadingsTable
{
    public const TYPE_INVOCATION = 0x01;
    public const TYPE_EXTENSION = 0x02;
    public const TYPE_DEFINITION = 0x03;

    /**
     * @var int[]
     */
    private const DEFINITIONS = [
        '#DirectiveDefinition' => self::TYPE_DEFINITION,
        '#EnumDefinition'      => self::TYPE_DEFINITION,
        '#InputDefinition'     => self::TYPE_DEFINITION,
        '#InterfaceDefinition' => self::TYPE_DEFINITION,
        '#ObjectDefinition'    => self::TYPE_DEFINITION,
        '#ScalarDefinition'    => self::TYPE_DEFINITION,
        '#SchemaDefinition'    => self::TYPE_DEFINITION,
        '#UnionDefinition'     => self::TYPE_DEFINITION,
        '#EnumExtension'       => self::TYPE_EXTENSION,
        '#InputExtension'      => self::TYPE_EXTENSION,
        '#InterfaceExtension'  => self::TYPE_EXTENSION,
        '#ObjectExtension'     => self::TYPE_EXTENSION,
        '#ScalarExtension'     => self::TYPE_EXTENSION,
        '#SchemaExtension'     => self::TYPE_EXTENSION,
        '#UnionExtension'      => self::TYPE_EXTENSION,
        '#Directive'           => self::TYPE_INVOCATION,
    ];

    /**
     * @var \SplPriorityQueue
     */
    private $records;

    /**
     * @var Factory
     */
    private $parser;

    /**
     * HeadingsTable constructor.
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function __construct()
    {
        $this->records = new \SplPriorityQueue();
        $this->parser  = Factory::create();
    }

    /**
     * @param Readable $file
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \RuntimeException
     */
    public function extract(Readable $file)
    {
        $ast = $this->parse($file);

        foreach ($ast->getChildren() as $child) {
            $this->records->insert(
                new Record($file, $child),
                $this->priority($child)
            );
        }
    }

    /**
     * @param Readable $file
     * @return RuleInterface|NodeInterface
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \RuntimeException
     */
    private function parse(Readable $file): RuleInterface
    {
        return $this->parser->parse($file);
    }

    /**
     * @param RuleInterface $rule
     * @return int
     * @throws BadAstMappingException
     */
    private function priority(RuleInterface $rule): int
    {
        /** @var int $priority */
        $priority = static::DEFINITIONS[$rule->getName()] ?? null;

        if ($priority === null) {
            throw new BadAstMappingException(\sprintf('Unprocessable AST Node %s', $rule->getName()));
        }

        return $priority;
    }
}
