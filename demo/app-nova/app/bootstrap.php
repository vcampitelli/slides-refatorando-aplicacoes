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
/** @var callable(ContainerBuilder<\DI\Container>): void $settings */
$settings = require __DIR__ . '/settings.php';
$settings($containerBuilder);

// Set up dependencies
/** @var callable(ContainerBuilder<\DI\Container>): void $dependencies */
$dependencies = require __DIR__ . '/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
/** @var callable(ContainerBuilder<\DI\Container>): void $repositories */
$repositories = require __DIR__ . '/repositories.php';
$repositories($containerBuilder);

// Build PHP-DI Container instance
return $containerBuilder->build();
