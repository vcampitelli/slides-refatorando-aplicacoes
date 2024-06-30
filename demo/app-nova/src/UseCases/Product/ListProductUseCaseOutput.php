<?php

declare(strict_types=1);

namespace App\UseCases\Product;

use App\Collection\ProductCollection;
use App\Models\Product;
use IteratorAggregate;
use Traversable;

readonly class ListProductUseCaseOutput implements IteratorAggregate
{
    public function __construct(public ProductCollection $collection)
    {
    }

    public function getIterator(): Traversable
    {
        return $this->collection->getIterator();
    }
}
