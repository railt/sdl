<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\TypeLoader;

/**
 * Class TypeLoader
 */
abstract class TypeLoader implements TypeLoaderInterface
{
    /**
     * @var string[]
     */
    private const DEFAULT_SCHEMA_EXTENSIONS = [
        'graphqls',
        'graphql',
        'gql',
    ];

    /**
     * @var array|string[]
     */
    protected $fileExtensions = self::DEFAULT_SCHEMA_EXTENSIONS;

    /**
     * @param string $extension
     * @return string
     */
    private function filterExtension(string $extension): string
    {
        return \ltrim(\trim($extension), '.');
    }

    /**
     * @param string $extension
     * @return TypeLoader|$this
     */
    public function withExtension(string $extension): self
    {
        $this->fileExtensions[] = $this->filterExtension($extension);

        return $this;
    }

    /**
     * @param string $extension
     * @return TypeLoader|$this
     */
    public function withoutExtension(string $extension): self
    {
        $extension = $this->filterExtension($extension);

        $filter = function (string $haystack) use ($extension): bool {
            return $haystack !== $extension;
        };

        $this->fileExtensions = \array_filter($this->fileExtensions, $filter);

        return $this;
    }
}
