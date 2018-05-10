<?php declare(strict_types=1);

use Railt\Io\File;
use Railt\SDL\Compiler;
use Railt\SDL\Exception\CompilerException;

require __DIR__ . '/../vendor/autoload.php';


$compiler = new Compiler();
$schema   = File::fromPathname(__DIR__ . '/schema.graphqls');

try {
    dd($compiler->parse($schema));
} catch (CompilerException $e) {
    echo $e;
    die;
}
