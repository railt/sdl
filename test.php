<?php

use Phplrt\Source\File;
use Psr\SimpleCache\InvalidArgumentException;
use Railt\SDL\Backend\Context;
use Railt\SDL\Backend\Context\Factory;
use Railt\SDL\Backend\NameResolver\HumanReadableResolver;
use Railt\SDL\Compiler;
use Railt\SDL\Spec\RawSpecification;
use Railt\TypeSystem\Schema;
use Railt\TypeSystem\Type\ObjectType;


require __DIR__ . '/vendor/autoload.php';


$autoload = fn(string $x) => new ObjectType($x);

try {
    $source = File::fromPathname(__DIR__ . '/test.graphql');

    $compiler = (new Compiler(new RawSpecification()));
    $compiler->autoload($autoload);

} catch (\Throwable|InvalidArgumentException $e) {
    echo $e;
}
