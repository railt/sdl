<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder;

use Railt\Io\Exception\NotReadableException;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Exception\FileNotReadableException;
use Railt\SDL\Frontend\AST\Scalar\ScalarInterface;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Record\RecordInterface;
use Railt\SDL\Frontend\Type\TypeNameInterface;

/**
 * Class IncludeBuilder
 */
class ImportBuilder extends BaseBuilder
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'ImportDefinition';
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return \Generator|RecordInterface|TypeNameInterface|void
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule)
    {
        /** @var ScalarInterface $value */
        $value = $rule->first('> #StringValue');

        yield from $this->load($this->include($ctx, $value));
    }

    /**
     * @param ContextInterface $ctx
     * @param ScalarInterface|RuleInterface $inclusion
     * @return Readable
     */
    private function include(ContextInterface $ctx, ScalarInterface $inclusion): Readable
    {
        try {
            return $this->tryOpen($ctx, $inclusion);
        } catch (FileNotReadableException $e) {
            throw $e->throwsIn($ctx->getFile(), $inclusion->getOffset());
        }
    }

    /**
     * @param ContextInterface $ctx
     * @param ScalarInterface $inclusion
     * @return Readable
     * @throws FileNotReadableException
     */
    private function tryOpen(ContextInterface $ctx, ScalarInterface $inclusion): Readable
    {
        $pathname = $this->getPathname($ctx->getFile(), $inclusion->toPrimitive());

        if ($ctx->getFile()->isFile()) {
            try {
                return File::fromPathname($pathname);
            } catch (NotReadableException $e) {
                $error = 'File "%s" not found or not readable.';
                throw new FileNotReadableException(\sprintf($error, $pathname), $e->getCode());
            }
        }

        $error = 'It is impossible to include an external file "%s" inside a non-physical file';
        throw new FileNotReadableException(\sprintf($error, $inclusion->toPrimitive()));
    }

    /**
     * @param Readable $file
     * @param string $inclusion
     * @return string
     */
    private function getPathname(Readable $file, string $inclusion): string
    {
        if (\in_array($inclusion[0], ['/', \DIRECTORY_SEPARATOR], true)) {
            return $inclusion;
        }

        $pathname = \dirname($file->getPathname()) . \DIRECTORY_SEPARATOR . $inclusion;
        $pathname = \str_replace('/./', '/', $pathname);

        return $pathname;
    }
}
