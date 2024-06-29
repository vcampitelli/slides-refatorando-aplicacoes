<?php

declare(strict_types=1);

namespace App\Repository;

use App\Collection\CollectionInterface;
use App\Models\ModelInterface;
use App\Persistence\DatabaseAdapterInterface;
use stdClass;

/**
 * @template TCollection of CollectionInterface
 * @template TModel of ModelInterface
 */
abstract class AbstractDatabaseRepository
{
    public function __construct(public readonly DatabaseAdapterInterface $databaseAdapter)
    {
    }

    /**
     * @return TCollection
     */
    protected function doFindAll(): CollectionInterface
    {
        $collection = $this->collection();
        foreach ($this->databaseAdapter->findAll($this->getTableName()) as $row) {
            $collection->add($this->unserialize($row));
        }
        return $collection;
    }

    /**
     * @param int $id
     * @return TModel|null
     */
    protected function doFindById(int $id): ?ModelInterface
    {
        return $this->doFindBy(['id = ?' => $id]);
    }

    /**
     * @param array $where
     * @return TModel|null
     */
    protected function doFindBy(array $where): ?ModelInterface
    {
        $row = $this->databaseAdapter->findOneBy(
            $this->getTableName(),
            $where
        );

        if ($row === null) {
            return null;
        }

        return $this->unserialize($row);
    }

    /**
     * @param TModel $model
     * @return TModel
     */
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
            ['id = ?' => $id]
        );
        return $model;
    }


    /**
     * @return TCollection
     */
    abstract protected function collection(): CollectionInterface;

    abstract protected function getTableName(): string;

    /**
     * @param TModel $model
     * @return stdClass
     * @throws \InvalidArgumentException If the model is not the one for this repository
     */
    abstract protected function serialize(ModelInterface $model): stdClass;

    /**
     * @param stdClass $row
     * @return TModel
     */
    abstract protected function unserialize(stdClass $row): ModelInterface;
}
