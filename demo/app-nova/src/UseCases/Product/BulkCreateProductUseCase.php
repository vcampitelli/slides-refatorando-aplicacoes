<?php

declare(strict_types=1);

namespace App\UseCases\Product;

use App\Models\Product;
use App\Repository\ProductRepositoryInterface;
use App\UseCases\UseCaseInterface;
use InvalidArgumentException;

readonly class BulkCreateProductUseCase implements UseCaseInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * @param BulkCreateProductUseCaseInput|null $input
     * @return BulkCreateProductUseCaseOutput
     */
    public function handle(BulkCreateProductUseCaseInput $input = null): BulkCreateProductUseCaseOutput
    {
        if (!$input) {
            throw new InvalidArgumentException();
        }

        foreach ($input->getIterator() as $row) {
            $product = new Product(
                id: null,
                idCategory: $row->idCategory,
                name: $row->name,
                sku: $row->sku,
                price: $row->price,
                active: true,
            );
            $product = $this->productRepository->save($product);
        }

        return new BulkCreateProductUseCaseOutput(
            $product,
        );
    }
}
