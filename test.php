<?php

use Railt\SDL\Compiler;
use Railt\SDL\Spec\RawSpecification;

require __DIR__ . '/vendor/autoload.php';


$compiler = (new Compiler(new RawSpecification()));
$compiler->rebuild();

$schema = $compiler->compile(/** @lang GraphQL */'
    type Y
    "asd" directive @a(x: Y) on FIELD
');


foreach ($schema->getTypeMap() as $type) {
    dump($type);
}

foreach ($schema->getDirectives() as $directive) {
    dump($directive);
}
