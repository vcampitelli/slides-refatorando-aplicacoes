<?php

declare(strict_types=1);

namespace App\Repository;

use App\Collection\ProductCollection;
use App\Models\Product;

interface ProductRepositoryInterface
{
    public function findAll(): ProductCollection;

    public function findById(int $id): ?Product;

    public function findBySku(string $sku): ?Product;

    public function save(Product $product): void;
}
