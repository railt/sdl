<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Backend;

use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator as JsonSchema;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\SDL\Exception\InternalException;
use Railt\SDL\IR\Definition;

/**
 * Class Validator
 */
class Validator
{
    /**
     * @var string
     */
    public const RAILT_SDL_1_2 = __DIR__ . '/../../resources/rlsdl-1.2.json';

    /**
     * @var string
     */
    protected const DEFAULT_VERSION = self::RAILT_SDL_1_2;

    /**
     * @var JsonSchema
     */
    private $validator;

    /**
     * @var array|object
     */
    private $schema;

    /**
     * Validator constructor.
     * @param Readable|null $schema
     */
    public function __construct(Readable $schema = null)
    {
        $this->validator = new JsonSchema();
        $this->schema    = $this->loadJsonSchema($schema);
    }

    /**
     * @param null|Readable|File $schema
     * @return mixed|array|object
     */
    private function loadJsonSchema(?Readable $schema)
    {
        $schema = $schema ?? File::fromPathname(static::DEFAULT_VERSION);

        return \json_decode($schema->getContents());
    }

    /**
     * @param Definition $definition
     * @return Definition
     * @throws InternalException
     */
    public function validate(Definition $definition): Definition
    {
        $json = $definition->toObject();

        $this->validator->reset();
        $this->validator->validate($json, $this->schema, Constraint::CHECK_MODE_VALIDATE_SCHEMA);

        if (! $this->validator->isValid()) {
            $error = \implode(\PHP_EOL, [
                'An internal representation code errors was found: ',
                $this->errorsToString($this->validator->getErrors()),
            ]);

            throw new InternalException($error);
        }

        return $definition;
    }

    /**
     * @param array $errors
     * @return string
     */
    private function errorsToString(array $errors): string
    {
        $applicator = function (array $error): string {
            if ($error['property']) {
                return \sprintf(' - [%s] %s.', $error['property'], $error['message']);
            }

            return \sprintf(' - %s.', $error['message']);
        };

        return \implode(\PHP_EOL, \array_map($applicator, $errors));
    }
}
