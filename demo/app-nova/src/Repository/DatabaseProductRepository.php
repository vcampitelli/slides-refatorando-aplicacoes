<?php

declare(strict_types=1);

namespace App\Repository;

use App\Collection\CollectionInterface;
use App\Collection\ProductCollection;
use App\Models\Product;
use App\Persistence\DatabaseAdapterInterface;
use stdClass;

class DatabaseProductRepository extends AbstractDatabaseRepository implements ProductRepositoryInterface
{
    public function findAll(): ProductCollection
    {
        return $this->doFindAll();
    }

    public function findById(int $id): ?Product
    {
        return $this->doFindById($id);
    }

    public function findBySku(string $sku): ?Product
    {
        return $this->doFindBy(['sku' => $sku]);
    }

    public function save(Product $product): void
    {
    }

    protected function getTableName(): string
    {
        return 'products';
    }

    protected function serialize(Product $model): stdClass
    {
        $object = new stdClass();
        $object->id = (isset($model->id)) ? $model->id : null;
        $object->idCategory = (isset($model->idCategory)) ? $model->idCategory : null;
        $object->name = $model->name;
        $object->sku = $model->sku;
        $object->price = $model->price;
        $object->active = $model->active;
        return $object;
    }

    protected function unserialize(stdClass $row): Product
    {
        return new Product(
            id: (isset($row->id)) ? (int) $row->id : null,
            idCategory: (isset($row->idCategory)) ? (int) $row->idCategory : null,
            name: $row->name,
            sku: $row->sku,
            price: (float) $row->price,
            active: (bool) $row->active,
        );
    }

    protected function collection(): ProductCollection
    {
        return new ProductCollection();
    }
}
