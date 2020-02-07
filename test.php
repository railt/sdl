<?php

use Phplrt\Source\File;
use Railt\SDL\Compiler;
use Railt\SDL\Spec\June2018;
use Railt\SDL\Spec\RawSpecification;

require __DIR__ . '/vendor/autoload.php';


$compiler = (new Compiler(new RawSpecification()));
$compiler->rebuild();

$schema = $compiler->compile(/** @lang GraphQL */'
    schema {
        query: Query
    }
    
    interface Query {}
');


foreach ($schema->getTypeMap() as $type) {
    echo $type->getName() . "\n";
}
