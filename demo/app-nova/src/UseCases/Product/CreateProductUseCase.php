<?php

declare(strict_types=1);

namespace App\UseCases\Product;

use App\Models\Product;
use App\Repository\ProductRepositoryInterface;
use App\UseCases\UseCaseInterface;
use InvalidArgumentException;

readonly class CreateProductUseCase implements UseCaseInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * @param CreateProductUseCaseInput|null $input
     * @return CreateProductUseCaseOutput
     */
    public function handle(CreateProductUseCaseInput $input = null): CreateProductUseCaseOutput
    {
        if (!$input) {
            throw new InvalidArgumentException();
        }

        $product = new Product(
            id: null,
            idCategory: $input->idCategory,
            name: $input->name,
            sku: $input->sku,
            price: $input->price,
            active: true,
        );
        $product = $this->productRepository->save($product);

        return new CreateProductUseCaseOutput(
            $product,
        );
    }
}
