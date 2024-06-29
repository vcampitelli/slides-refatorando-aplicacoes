<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Repository\ProductRepositoryInterface;

class ViewProductUseCase
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function handle(int $id): ViewProductUseCaseOutput
    {
        $product = $this->productRepository->findById($id);

        return new ViewProductUseCaseOutput(
            $product,
        );
    }
}
