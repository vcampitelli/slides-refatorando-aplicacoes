<?php

declare(strict_types=1);

namespace App\UseCases\Product\BulkCreate;

use ArrayIterator;
use App\UseCases\Product\CreateProductUseCaseInput;
use Traversable;

/** @implements \IteratorAggregate<int, CreateProductUseCaseInput> */
class BulkCreateProductUseCaseInput implements \IteratorAggregate
{
    /** @var ArrayIterator<int, CreateProductUseCaseInput> */
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
        $this->iterator[] = new CreateProductUseCaseInput($idCategory, $name, $sku, $price, $active);
        return $this;
    }

    /**
     * @return Traversable<int, CreateProductUseCaseInput>
     */
    public function getIterator(): Traversable
    {
        return $this->iterator;
    }
}
