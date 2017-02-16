<?php

ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('xdebug.var_display_max_depth', -1);

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
if (file_exists('vendor/autoload.php')) {
    include 'vendor/autoload.php';
}else{
    throw new RuntimeException('Unable to autoload');
}

//$config = include('config/sample.product.catalog.config.php');
$config = require 'config/sample.blog.config.php';

foreach ($config as $namespace => $structure) {
    $gen = new \CqrsEsAgility\Generate($namespace, $structure);
    $gen->generate();
}