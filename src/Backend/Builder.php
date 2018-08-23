<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Backend;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Railt\Io\Readable;
use Railt\Reflection\Contracts\Document as DocumentInterface;
use Railt\Reflection\Contracts\Reflection;
use Railt\Reflection\Document;
use Railt\SDL\Backend\Context\GlobalContext;

/**
 * Class Builder
 */
class Builder implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var GlobalContext
     */
    private $context;

    /**
     * @var Reflection
     */
    private $root;

    /**
     * Process constructor.
     * @param Reflection $root
     */
    public function __construct(Reflection $root)
    {
        $this->root    = $root;
        $this->context = new GlobalContext();
        $this->exportTypes($root);
    }

    /**
     * @param Reflection $reflection
     */
    private function exportTypes(Reflection $reflection): void
    {
        foreach ($reflection->getDocuments() as $document) {
            $context = $this->context->fromDocument($document);

            foreach ($document->getDefinitions() as $type) {
                $context->create($type);
            }
        }
    }

    /**
     * @param Readable $file
     * @param iterable $opcodes
     * @return Document|DocumentInterface
     */
    public function run(Readable $file, iterable $opcodes): Document
    {
        $context = $this->context->fromDocument(new Document($this->root, $file));

        if ($this->logger) {
            $this->logger->debug(\sprintf('Create %s', $context->getDocument()));
        }

        foreach ($opcodes as $code) {
            if ($this->logger) {
                $this->logger->debug(\sprintf('> %s', $code));
            }
        }

        return $context->getDocument();
    }
}
