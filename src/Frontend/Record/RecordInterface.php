<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Record;

use Railt\Parser\Finder;
use Railt\SDL\Frontend\Type\TypeInterface;
use Railt\SDL\Frontend\Type\TypeNameInterface;

/**
 * Interface RecordInterface
 */
interface RecordInterface
{
    /**
     * @return TypeNameInterface
     */
    public function getName(): TypeNameInterface;

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;

    /**
     * @param string $query
     * @return Finder
     */
    public function find(string $query): Finder;

    /**
     * @return iterable|ArgumentInterface[]
     */
    public function getArguments(): iterable;

    /**
     * @param ArgumentInterface $argument
     * @return RecordInterface
     */
    public function addArgument(ArgumentInterface $argument): RecordInterface;
}
