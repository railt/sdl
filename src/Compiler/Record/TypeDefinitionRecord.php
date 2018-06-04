<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Record;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Common\TypeName;
use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Class TypeDefinitionRecord
 */
abstract class TypeDefinitionRecord extends Record
{
    /**
     * @var TypeName
     */
    private $name;

    /**
     * TypeDefinitionRecord constructor.
     * @param LocalContextInterface $context
     * @param RuleInterface $ast
     */
    public function __construct(LocalContextInterface $context, RuleInterface $ast)
    {
        parent::__construct($context, $ast);
    }
}
