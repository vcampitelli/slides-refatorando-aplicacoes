<?php

declare(strict_types=1);

namespace App\Persistence;

use App\Collection\CollectionInterface;
use App\Persistence\Pdo\PdoWrapper;
use Exception;
use Generator;
use RuntimeException;
use stdClass;

readonly class PdoDatabaseAdapter implements DatabaseAdapterInterface
{
    public function __construct(
        private PdoWrapper $pdo,
    ) {
    }

    /**
     * @param string $tableName
     * @return Generator<stdClass>
     */
    public function findAll(string $tableName): Generator
    {
        $select = new Pdo\Statement\SelectPdoStatement($this->pdo);
        return $select($tableName);
    }

    public function findOneBy(
        string $tableName,
        array $where,
    ): ?stdClass {
        $select = new Pdo\Statement\SelectPdoStatement($this->pdo);
        foreach ($select($tableName, $where, 1) as $row) {
            return $row;
        }
        return null;
    }

    public function insert(string $tableName, stdClass $values): string
    {
        $statement = new Pdo\Statement\InsertPdoStatement($this->pdo);
        return $statement($tableName, (array) $values);
    }

    public function update(string $tableName, stdClass $values, array $where): int
    {
        $statement = new Pdo\Statement\UpdatePdoStatement($this->pdo);
        return $statement($tableName, (array) $values, $where);
    }
}
