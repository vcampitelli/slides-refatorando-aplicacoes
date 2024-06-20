<?php

declare(strict_types=1);

use App\Persistence\DatabaseAdapterInterface;
use App\Persistence\MedooDatabaseAdapter;
use DI\ContainerBuilder;
use Medoo\Medoo;

use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        Medoo::class => function () {
            return new Medoo([
                'type' => $_ENV['DATABASE_TYPE'],
                'host' => $_ENV['DATABASE_HOST'],
                'database' => $_ENV['DATABASE_DATABASE'],
                'username' => $_ENV['DATABASE_USERNAME'],
                'password' => $_ENV['DATABASE_PASSWORD'],
            ]);
        },
        DatabaseAdapterInterface::class => autowire(MedooDatabaseAdapter::class),
    ]);
};
