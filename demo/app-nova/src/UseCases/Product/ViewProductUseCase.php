<?php

declare(strict_types=1);

namespace App\UseCases\Product;

use App\Repository\ProductRepositoryInterface;
use App\UseCases\UseCaseInterface;
use InvalidArgumentException;

readonly class ViewProductUseCase implements UseCaseInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * @param ViewProductUseCaseInput|null $input
     * @return ViewProductUseCaseOutput
     */
    public function handle(ViewProductUseCaseInput $input = null): ViewProductUseCaseOutput
    {
        if (!$input) {
            throw new InvalidArgumentException();
        }

        $product = $this->productRepository->findById($input->id);

        return new ViewProductUseCaseOutput(
            $product,
        );
    }
}
