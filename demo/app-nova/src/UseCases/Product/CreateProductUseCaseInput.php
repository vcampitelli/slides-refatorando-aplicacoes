<?php

declare(strict_types=1);

namespace App\UseCases\Product;

readonly class CreateProductUseCaseInput
{
    public function __construct(
        public int $idCategory,
        public string $name,
        public string $sku,
        public float $price,
        public bool $active
    ) {
    }
}
