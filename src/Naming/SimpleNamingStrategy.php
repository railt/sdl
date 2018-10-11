<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Naming;

use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class SimpleNamingStrategy
 */
class SimpleNamingStrategy extends Strategy
{
    /**
     * SimpleNamingStrategy constructor.
     */
    public function __construct()
    {
        parent::__construct(function (TypeNameInterface $name, iterable $arguments) {
            return $this->format($name, $arguments);
        });
    }

    /**
     * @param TypeNameInterface $name
     * @param iterable $arguments
     * @return string
     */
    private function format(TypeNameInterface $name, iterable $arguments): string
    {
        echo \str_repeat('-', 100) . "\n";
        echo $name . ' ';
        \dump($arguments);

        return $this->formatName($name);
    }

    /**
     * @param TypeNameInterface $name
     * @return string
     */
    private function formatName(TypeNameInterface $name): string
    {
        $from = TypeNameInterface::NAMESPACE_SEPARATOR;

        return \str_replace($from, '_', $name->getFullyQualifiedName());
    }
}
