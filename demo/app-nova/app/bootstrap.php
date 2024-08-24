<?php

declare(strict_types=1);

use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

if (($_ENV['ENVIRONMENT'] ?? '') === 'production') {
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// Set up settings
$settings = require __DIR__ . '/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
$repositories = require __DIR__ . '/repositories.php';
$repositories($containerBuilder);

// Build PHP-DI Container instance
return $containerBuilder->build();
