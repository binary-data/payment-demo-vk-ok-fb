<?php

declare(strict_types=1);

use Binarydata\Shpongle\App;
use DI\ContainerBuilder;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;

define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);

require ROOT . 'vendor/autoload.php';

$container = (new ContainerBuilder())
    ->addDefinitions(
        ROOT . 'vendor/binarydata/shpongle/src/di.php',
        ROOT . 'config/di.php'
    )
    ->build();

$creator = $container->get(ServerRequestCreatorInterface::class);
$request = $creator->fromGlobals();

$app = $container->get(App::class);

$response = $app->handle($request);

($container->get(EmitterInterface::class))->emit($response);
