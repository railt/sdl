<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Value;

use Railt\Io\Readable;
use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Value;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class InputValue
 */
class InputValue implements ValueInterface
{
    /**
     * @var RuleInterface
     */
    private $rule;

    /**
     * @var Readable
     */
    private $file;

    /**
     * ListValue constructor.
     * @param RuleInterface $rule
     * @param Readable $file
     */
    public function __construct(RuleInterface $rule, Readable $file)
    {
        $this->rule = $rule;
        $this->file = $file;
    }

    /**
     * @param NodeInterface $rule
     * @return bool
     */
    public static function match(NodeInterface $rule): bool
    {
        return $rule->getName() === 'Input';
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->rule->getOffset();
    }

    /**
     * @return array
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function toScalar(): array
    {
        return \iterator_to_array($this->getScalarValues());
    }

    /**
     * @return \Traversable|mixed[]
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function getScalarValues(): \Traversable
    {
        foreach ($this->getValues() as $key => $value) {
            yield $key => $value->toScalar();
        }
    }

    /**
     * @return ValueInterface[]|\Generator
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function getValues(): \Traversable
    {
        $keys = [];

        /** @var RuleInterface $child */
        foreach ($this->rule as $child) {
            $key    = $this->getKey($child);
            $scalar = $key->toScalar();

            if (\in_array($scalar, $keys, true)) {
                $error = \sprintf('Key "%s" duplication in an input type', $scalar);
                throw (new TypeConflictException($error))
                    ->throwsIn($this->file, $key->getOffset());
            }

            yield $keys[] = $scalar => Value::parse($child->first('Value', 1), $this->file);
        }
    }

    /**
     * @param RuleInterface $rule
     * @return ValueInterface
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function getKey(RuleInterface $rule): ValueInterface
    {
        /** @var RuleInterface $key */
        $key = $rule->first('Key', 1);

        return Value::parse($key, $this->file);
    }
}
