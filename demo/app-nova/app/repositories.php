<?php

declare(strict_types=1);

use App\Repository\DatabaseProductRepository;
use App\Repository\ProductRepositoryInterface;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        ProductRepositoryInterface::class => \DI\autowire(DatabaseProductRepository::class),
    ]);
};
