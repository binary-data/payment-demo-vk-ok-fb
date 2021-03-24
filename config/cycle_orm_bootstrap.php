<?php

declare(strict_types=1);

use Cycle\Bootstrap;
use Doctrine\Common\Annotations\AnnotationRegistry;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

/** @noinspection PhpDeprecationInspection */
AnnotationRegistry::registerLoader('class_exists');

$db = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'db.sqlite';
$config = Bootstrap\Config::forDatabase("sqlite:$db");
$config = $config->withEntityDirectory(dirname(__DIR__). DIRECTORY_SEPARATOR . 'src');
$orm = Bootstrap\Bootstrap::fromConfig($config);
