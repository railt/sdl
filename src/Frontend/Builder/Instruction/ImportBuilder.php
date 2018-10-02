<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Instruction;

use Railt\Io\Exception\NotReadableException;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Exception\FileNotReadableException;
use Railt\SDL\Exception\InvalidArgumentException;
use Railt\SDL\Frontend\Builder\BaseBuilder;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\IR\SymbolTable\ValueInterface;
use Railt\SDL\IR\Type;

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
     * @return mixed|\Generator|void
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule)
    {
        /** @var ValueInterface $value */
        $value = yield $rule->getChild(0);

        if ($value->getType()->typeOf(Type::string())) {
            yield from $this->load($this->include($ctx, (string)$value->getValue()));
        }

        $error = 'Argument of include should be a string, but %s given';
        throw new InvalidArgumentException(\sprintf($error, $value->getType()));
    }

    /**
     * @param ContextInterface $ctx
     * @param string $inclusion
     * @return Readable
     */
    private function include(ContextInterface $ctx, string $inclusion): Readable
    {
        return $this->tryOpen($ctx, $inclusion);
    }

    /**
     * @param ContextInterface $ctx
     * @param string $inclusion
     * @return Readable
     * @throws FileNotReadableException
     */
    private function tryOpen(ContextInterface $ctx, string $inclusion): Readable
    {
        $pathname = $this->getPathname($ctx->getFile(), $inclusion);

        if ($ctx->getFile()->isFile()) {
            try {
                return File::fromPathname($pathname);
            } catch (NotReadableException $e) {
                $error = 'File "%s" not found or not readable';
                throw new FileNotReadableException(\sprintf($error, $pathname), $e->getCode());
            }
        }

        $error = 'It is impossible to include an external file "%s" inside a non-physical file';
        throw new FileNotReadableException(\sprintf($error, $inclusion));
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
