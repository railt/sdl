<?php

/**
 * This file is part of sdl package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Phplrt\Source\Exception\NotFoundException;
use Phplrt\Source\Exception\NotReadableException;
use Psr\Log\LoggerAwareTrait;
use Railt\SDL\Frontend\Generator;

/**
 * Trait DevelopmentModeFacadeTrait
 */
trait DevelopmentModeFacadeTrait
{
    use LoggerAwareTrait;

    /**
     * @return $this
     * @throws \Throwable
     */
    public function rebuild(): self
    {
        (new Generator())->generateAndSave();

        return $this;
    }
}
