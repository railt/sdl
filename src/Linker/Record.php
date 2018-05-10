<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\Io\Readable;

/**
 * Class Record
 */
class Record
{
    /**
     * @var Readable
     */
    private $file;

    /**
     * @var RuleInterface
     */
    private $rule;

    /**
     * Record constructor.
     * @param Readable $file
     * @param RuleInterface $rule
     */
    public function __construct(Readable $file, RuleInterface $rule)
    {
        $this->file = $file;
        $this->rule = $rule;
    }
}
