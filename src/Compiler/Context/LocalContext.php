<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Context;

use Railt\Io\Readable;
use Railt\SDL\Compiler\Component\TypeName;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class LocalContext
 */
class LocalContext extends Context implements LocalContextInterface
{
    /**
     * @var GlobalContextInterface
     */
    private $global;

    /**
     * @var LocalContextInterface|null
     */
    private $previous;

    /**
     * @var Readable
     */
    private $file;

    /**
     * @var TypeName
     */
    private $name;

    /**
     * LocalContext constructor.
     * @param GlobalContextInterface $global
     * @param Readable $file
     * @param TypeName $name
     */
    public function __construct(GlobalContextInterface $global, Readable $file, TypeName $name)
    {
        $this->global   = $global;
        $this->previous = $global->count() ? $global->current() : null;
        $this->file     = $file;
        $this->name     = $name;
    }

    /**
     * @return TypeName
     */
    public function getName(): TypeName
    {
        return $this->name;
    }

    /**
     * @return CallStackInterface
     */
    public function getCallStack(): CallStackInterface
    {
        return $this->global->getCallStack();
    }

    /**
     * @param TypeName $name
     * @param Readable|null $file
     * @return LocalContextInterface
     */
    public function create(TypeName $name, Readable $file = null): LocalContextInterface
    {
        return $this->global->create($name, $file ?? $this->file);
    }

    /**
     * @return LocalContextInterface
     */
    public function current(): LocalContextInterface
    {
        return $this->global->current();
    }

    /**
     * @return LocalContextInterface
     */
    public function complete(): LocalContextInterface
    {
        return $this->global->complete();
    }

    /**
     * @return ContextInterface
     */
    public function previous(): ContextInterface
    {
        \assert($this->previous, 'Internal Error: Accessing to prev context via protected root context');

        return $this->previous;
    }

    /**
     * @return GlobalContextInterface
     */
    public function global(): GlobalContextInterface
    {
        return $this->global;
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->file;
    }
}
