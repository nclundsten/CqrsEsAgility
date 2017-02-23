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

$namespaces = require 'config/sample.hockey.config.php';
$generatorsConfig = require 'config/generator.config.php';

foreach ($namespaces as $namespaceName => $namespaceConfig) {

    $fileCollection = new CqrsEsAgility\Files\FilesCollection();
    $generate = new CqrsEsAgility\Generate(
        $fileCollection,
        new CqrsEsAgility\Generator\Command($generatorsConfig, $namespaceName, $fileCollection),
        new CqrsEsAgility\Generator\CommandHandler($generatorsConfig, $namespaceName, $fileCollection),
        new CqrsEsAgility\Generator\Aggregate($generatorsConfig, $namespaceName, $fileCollection),
        new CqrsEsAgility\Generator\Event($generatorsConfig, $namespaceName, $fileCollection),
        new CqrsEsAgility\Generator\Listener($generatorsConfig, $namespaceName, $fileCollection),
        new CqrsEsAgility\Generator\Projector($generatorsConfig, $namespaceName, $fileCollection)
    );
    $generate($namespaceConfig);
}