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
            if (
                !is_array($config) || !isset($config['dsn']) || !is_string($config['dsn'])
                || (isset($config['username']) && !is_string($config['username']))
                || (isset($config['password']) && !is_string($config['password']))
            ) {
                throw new RuntimeException('Configuração de banco de dados inválida');
            }
            return new PdoWrapper(
                $config['dsn'],
                $config['username'] ?? null,
                $config['password'] ?? null,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
            );
        },
        DatabaseAdapterInterface::class => autowire(PdoDatabaseAdapter::class),
    ]);
};
