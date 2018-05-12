<?php declare(strict_types=1);

use Railt\Io\File;
use Railt\SDL\Compiler;
use Railt\SDL\Exception\CompilerException;

require __DIR__ . '/../vendor/autoload.php';


try {
    $compiler = new Compiler();
    $schema   = File::fromPathname(__DIR__ . '/schema.gql');

    dd($compiler->parse($schema));
} catch (Throwable $e) {
    echo $e;
    die;
}
