<?php

use Railt\SDL\Compiler;
use Railt\SDL\Spec\RawSpecification;

require __DIR__ . '/vendor/autoload.php';

$compiler = (new Compiler(new RawSpecification()));
$compiler->rebuild();

$schema = $compiler->compile('
    "descr" directive @a repeatable on FIELD_DEFINITION
    "descr" enum A 
    "descr" interface B<T> implements C<T> {}
    "descr" type C implements B<C> {
        field(arg: C = $var): C
    }
    "descr" scalar D
    "descr" union E
');


foreach ($schema->getTypeMap() as $type) {
    dump($type);
}

foreach ($schema->getDirectives() as $type) {
    dump($type);
}
