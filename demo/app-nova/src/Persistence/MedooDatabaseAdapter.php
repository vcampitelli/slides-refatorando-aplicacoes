<?php

declare(strict_types=1);

namespace App\Persistence;

use App\Collection\CollectionInterface;
use Medoo\Medoo;
use stdClass;

readonly class MedooDatabaseAdapter implements DatabaseAdapterInterface
{
    public function __construct(
        private Medoo $database
    ) {
    }

    /**
     * @param string $tableName
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function findAll(
        string $tableName,
        CollectionInterface $collection,
    ): CollectionInterface {
        $this->database->select($tableName, [], '*', null, function ($row) use ($collection) {
            $model = $this->unserialize($row);
            $collection->add($model);
        });
        return $collection;
    }

    public function findOneBy(
        string $tableName,
        array $where,
    ): ?stdClass {
        $model = null;
        $this->database->select($tableName, [], '*', $where, function ($row) use (&$model) {
            $model = $row;
        });
        return $model;
    }

    public function insert(string $tableName, stdClass $values): string
    {
        $this->database->insert($tableName, (array) $values);
        $id = $this->database->id();
        if (empty($id)) {
            throw new \Exception('Erro ao inserir item'); // @FIXME
        }
        return $id;
    }

    public function update(string $tableName, stdClass $values, array $where): int
    {
        $statement = $this->database->update($tableName, (array) $values, $where);
        if ($statement === null) {
            throw new \Exception('Erro ao atualizar item'); // @FIXME
        }
        return $statement->rowCount();
    }
}
