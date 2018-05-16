<?php declare(strict_types=1);

use Railt\Io\File;
use Railt\SDL\Compiler;

require __DIR__ . '/../vendor/autoload.php';

try {
    $c = new Compiler();
    $c->parse(File::fromPathname(__DIR__ . '/schema.gql'));
} catch (\Throwable $e) {
    echo $e;
    die;
}
