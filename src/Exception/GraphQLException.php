<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Exception;

use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Position\Position;
use Phplrt\Position\PositionInterface;
use Phplrt\Source\Exception\NotAccessibleException;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class GraphQLException
 */
class GraphQLException extends \RuntimeException
{
    /**
     * GraphQLException constructor.
     *
     * @param string $message
     * @param Node|null $node
     * @param \Throwable|null $prev
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function __construct(string $message, Node $node = null, \Throwable $prev = null)
    {
        if ($node) {
            $source = $node->loc->source;
            $position = Position::fromOffset($source, $node->getOffset());

            $message .= $this->extendMessage($source, $position);

            if ($node->loc && $node->loc->source instanceof FileInterface) {
                $this->file = $source->getPathname();
                $this->line = $position->getLine();
            }
        }

        parent::__construct($message, 0, $prev);
    }

    /**
     * @param ReadableInterface $file
     * @param PositionInterface $position
     * @return string
     */
    private function extendMessage(ReadableInterface $file, PositionInterface $position): string
    {
        return (new Highlight())->read($file, $position);
    }
}
