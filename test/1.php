<?php declare(strict_types=1);

use Railt\Io\File;
use Railt\SDL\Compiler;

require __DIR__ . '/../vendor/autoload.php';

$e = new Compiler\Entity();
$e->add(new class() implements Compiler\Component\ComponentInterface {
});
echo $e->has(Compiler\Component\ComponentInterface::class);
die;

try {
    $compiler = new Compiler();
    $schema   = File::fromPathname(__DIR__ . '/schema.gql');

    dd($compiler->parse($schema));
} catch (Throwable $e) {
    echo $e;
    die;
}
