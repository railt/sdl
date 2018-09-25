<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\Rule;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\Type\AtGlobal;
use Railt\SDL\Frontend\Type\TypeName;
use Railt\SDL\Frontend\Type\TypeNameInterface;

/**
 * Class TypeNameNode
 */
class TypeNameNode extends Rule
{
    /**
     * @param bool $atRoot
     * @return TypeNameInterface
     */
    public function toTypeName(bool $atRoot = false): TypeNameInterface
    {
        if ($atRoot || $this->first('> #AtRoot') instanceof RuleInterface) {
            return new AtGlobal($this->getNameChunks());
        }

        return new TypeName($this->getNameChunks());
    }

    /**
     * @return array|string[]
     */
    private function getNameChunks(): array
    {
        $result = [];

        /** @var LeafInterface $leaf */
        foreach ($this->find(':T_NAME') as $leaf) {
            $result[] = $leaf->getValue();
        }

        return $result;
    }
}
