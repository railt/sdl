<?php

use Railt\SDL\Compiler;

require __DIR__ . '/vendor/autoload.php';

$compiler = (new Compiler());
$compiler->rebuild();

$schema = $compiler->compile(/** @lang Gherkin */'
    type Paginator<out T> {
        items: [T!]!
    }

    type Storage<in T> {
        put(value: T): Any
    }

    type Users {
        storage: Storage<UserInput>
        all: Paginator<User>
    }

    type User {}
    input UserInput {}
');


foreach ($schema->getTypeMap() as $type) {
    dump($type);
}

foreach ($schema->getDirectives() as $type) {
    dump($type);
}
