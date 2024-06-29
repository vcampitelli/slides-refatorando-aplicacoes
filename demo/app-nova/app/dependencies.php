<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Persistence\DatabaseAdapterInterface;
use App\Persistence\Pdo\PdoWrapper;
use App\Persistence\PdoDatabaseAdapter;
use DI\ContainerBuilder;

use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        PdoWrapper::class => function (SettingsInterface $settings) {
            $config = $settings->get('database');
            return new PdoWrapper(
                $config['dsn'],
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
            );
        },
        DatabaseAdapterInterface::class => autowire(PdoDatabaseAdapter::class),
    ]);
};
