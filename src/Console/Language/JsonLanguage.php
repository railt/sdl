<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Console\Language;

use Railt\Console\Language\Language;
use Railt\Io\Readable;

/**
 * Class JsonLanguage
 */
class JsonLanguage extends Language
{
    /**
     * @return array
     */
    public function tokens(): array
    {
        return [
            'T_STRING'  => '"[^"\\\\]*(\\\\.[^"\\\\]*)*"(?!:)',
            'T_KEY'     => '"[^"\\\\]*(\\\\.[^"\\\\]*)*"',
            'T_DIGIT'   => '(\d*\.)?\d+',
            'T_COMMA'   => ',',
            'T_ARR'     => '\[\]',
            'T_KEYWORD' => 'null|false|true',
            'T_PAIR'    => '[{:}]',
        ];
    }

    /**
     * @return array
     */
    public function colors(): array
    {
        return [
            'T_KEY'     => 'fg=green',
            'T_STRING'  => 'fg=yellow',
            'T_COMMA'   => 'fg=blue',
            'T_KEYWORD' => 'fg=yellow',
            'T_ARR'     => 'fg=yellow',
            'T_PAIR'    => 'fg=blue;options=bold',
        ];
    }

    /**
     * @param Readable $file
     * @return bool
     */
    public function match(Readable $file): bool
    {
        return $this->matchExtension($file, ['json']);
    }
}
