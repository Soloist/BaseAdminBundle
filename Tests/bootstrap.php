<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

/** @var $classLoader \Composer\Autoload\ClassLoader */
$classLoader = null;
if (is_file($autoloadPath = __DIR__.'/../vendor/autoload.php')) {
    $classLoader = require_once $autoloadPath;
} else {
    $classLoader = require_once __DIR__.'/../../../../../vendor/autoload.php';
}

AnnotationRegistry::registerLoader(array($classLoader, 'loadClass'));

