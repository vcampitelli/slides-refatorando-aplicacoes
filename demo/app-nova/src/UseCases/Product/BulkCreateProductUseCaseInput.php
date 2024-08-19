<?php

declare(strict_types=1);

namespace App\UseCases\Product;

use ArrayIterator;
use stdClass;
use Traversable;

class BulkCreateProductUseCaseInput implements \IteratorAggregate
{
    private ArrayIterator $iterator;

    public function __construct()
    {
        $this->iterator = new ArrayIterator();
    }

    public function add(
        int $idCategory,
        string $name,
        string $sku,
        float $price,
        bool $active
    ): self {
        $object = new stdClass();
        $object->idCategory = $idCategory;
        $object->name = $name;
        $object->sku = $sku;
        $object->price = $price;
        $object->active = $active;
        $this->iterator[] = $object;
        return $this;
    }

    /**
     * @return Traversable<stdClass>
     */
    public function getIterator(): Traversable
    {
        return $this->iterator;
    }
}
