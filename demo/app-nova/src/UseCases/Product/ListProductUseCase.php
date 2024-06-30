<?php

declare(strict_types=1);

namespace App\UseCases\Product;

use App\Repository\ProductRepositoryInterface;
use App\UseCases\UseCaseInterface;
use InvalidArgumentException;

readonly class ListProductUseCase implements UseCaseInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * @return ListProductUseCaseOutput
     */
    public function handle(): ListProductUseCaseOutput
    {
        $collection = $this->productRepository->findAll();

        return new ListProductUseCaseOutput(
            $collection,
        );
    }
}
