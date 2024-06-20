<?php

declare(strict_types=1);

namespace App\Repository;

use App\Collection\CollectionInterface;
use App\Collection\ProductCollection;
use App\Models\ModelInterface;
use App\Models\Product;
use App\Persistence\DatabaseAdapterInterface;
use stdClass;

abstract class AbstractDatabaseRepository
{
    public function __construct(protected readonly DatabaseAdapterInterface $databaseAdapter)
    {
    }

    protected function doFindAll(): CollectionInterface
    {
        return $this->databaseAdapter->findAll(
            $this->getTableName(),
            $this->collection(),
        );
    }

    protected function doFindById(int $id): ?ModelInterface
    {
        return $this->doFindBy(['id' => $id]);
    }

    protected function doFindBy(array $where): ?ModelInterface
    {
        $row = $this->databaseAdapter->findOneBy(
            $this->getTableName(),
            $where
        );
        return ($row === null) ? null : $this->unserialize($row);
    }

    protected function doSave(ModelInterface $model): ModelInterface
    {
        $id = $model->getId();
        if ($id === null) {
            $id = (int) $this->databaseAdapter->insert(
                $this->getTableName(),
                $this->serialize($model)
            );
            return $model->withId($id);
        }

        $this->databaseAdapter->update(
            $this->getTableName(),
            $this->serialize($model),
            ['id' => $id]
        );
        return $model;
    }


    abstract protected function collection(): CollectionInterface;

    abstract protected function getTableName(): string;

    abstract protected function serialize(ModelInterface $model): stdClass;

    abstract protected function unserialize(stdClass $row): ModelInterface;

}
