<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Invocation;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\AST\Definition\ArgumentDefinitionNode;
use Railt\SDL\IR\Value;
use Railt\SDL\IR\ValueInterface;

/**
 * Class InputValue
 */
class InputValueNode extends AbstractValueNode
{
    /**
     * @return \Generator|mixed
     */
    protected function parse()
    {
        /** @var ArgumentValueNode $argument */
        foreach ($this->find('ArgumentValue', 1) as $argument) {
            yield from $argument->toPrimitive();
        }
    }

    /**
     * @param Readable $file
     * @return ValueInterface
     */
    public function toValue(Readable $file): ValueInterface
    {
        $result = [];

        /** @var ArgumentValueNode $argument */
        foreach ($this->find('ArgumentValue', 1) as $argument) {
            $result[$argument->getFullName()] = $argument->toValue($file);
        }

        return (new Value($result))->in($file, $this->getOffset());
    }
}
