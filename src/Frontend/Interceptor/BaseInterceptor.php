<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Interceptor;

use Railt\SDL\Frontend\Builder;
use Railt\SDL\Frontend\SymbolTable;

/**
 * Class BaseInterceptor
 */
abstract class BaseInterceptor implements InterceptorInterface
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var SymbolTable
     */
    protected $table;

    /**
     * BaseInterceptor constructor.
     * @param Builder $builder
     * @param SymbolTable $table
     */
    public function __construct(Builder $builder, SymbolTable $table)
    {
        $this->builder = $builder;
        $this->table = $table;
    }
}
