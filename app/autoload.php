<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

// Storage path for Lazer flat file database library
// This resolves to /app/storage
define('LAZER_DATA_PATH', realpath(dirname(__FILE__)) . '/storage/');

return $loader;
