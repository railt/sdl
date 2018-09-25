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
use Railt\SDL\Frontend\Builder;
use Railt\SDL\Frontend\Record\RecordInterface;

/**
 * Class BaseBuilder
 */
abstract class BaseBuilder implements BuilderInterface
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * BaseBuilder constructor.
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param Readable $readable
     * @return iterable|RecordInterface[]|\Traversable
     */
    protected function load(Readable $readable)
    {
        return $this->builder->load($readable);
    }
}
