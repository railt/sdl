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
use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Interface RecordInterface
 */
interface RecordInterface extends EntityInterface
{
    /**
     * @return LocalContextInterface
     */
    public function getContext(): LocalContextInterface;

    /**
     * @return RuleInterface
     */
    public function getAst(): RuleInterface;
}
