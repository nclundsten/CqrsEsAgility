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

$gen = CqrsEsAgility\Factory\GenerateFactory::create(require 'config/generator.config.php');

$gen->generateNamespaces(
    new CqrsEsAgility\Config\NamespacesConfig(require 'config/sample.hockey.config.php')
);
