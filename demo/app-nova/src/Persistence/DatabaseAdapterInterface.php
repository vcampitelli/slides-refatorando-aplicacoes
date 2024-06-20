<?php

declare(strict_types=1);

namespace App\Persistence;

use App\Collection\CollectionInterface;
use stdClass;

interface DatabaseAdapterInterface
{
    /**
     * @param string $tableName
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function findAll(
        string $tableName,
        CollectionInterface $collection,
    ): CollectionInterface;

    public function findOneBy(
        string $tableName,
        array $where,
    ): ?stdClass;

    /**
     * @param string $tableName
     * @param stdClass $values
     * @return string ID inserido
     */
    public function insert(
        string $tableName,
        stdClass $values,
    ): string;

    /**
     * @param string $tableName
     * @param stdClass $values
     * @param array $where
     * @return int Linhas afetadas
     */
    public function update(
        string $tableName,
        stdClass $values,
        array $where,
    ): int;
}
