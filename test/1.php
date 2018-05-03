<?php use Railt\Io\File;
use Railt\SDL\Compiler;

require __DIR__ . '/../vendor/autoload.php';

$compiler = new Compiler();
$schema = File::fromPathname(__DIR__ . '/schema.graphqls');

dd($compiler->parse($schema));
