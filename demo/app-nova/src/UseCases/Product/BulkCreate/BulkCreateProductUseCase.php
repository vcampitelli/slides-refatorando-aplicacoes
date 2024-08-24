<?php

declare(strict_types=1);

namespace App\UseCases\Product\BulkCreate;

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

        $count = 0;
        $errors = new BulkCreateProductUseCaseOutputErrors();

        foreach ($input->getIterator() as $i => $row) {
            try {
                $product = $this->findOrCreateProductByRow($row);
                $this->productRepository->save($product);
                $count++;
            } catch (\Throwable $t) {
                $errors->add($i, $t->getMessage());
            }
        }

        return new BulkCreateProductUseCaseOutput(
            count: $count,
            errors: $errors,
        );
    }

    protected function findOrCreateProductByRow(\stdClass $row): Product
    {
        $product = $this->productRepository->findBySku($row->sku);
        if (!$product) {
            return new Product(
                id: null,
                idCategory: $row->idCategory,
                name: $row->name,
                sku: $row->sku,
                price: $row->price,
                active: $row->active,
            );
        }

        return $product
            ->withIdCategory($row->idCategory)
            ->withName($row->name)
            ->withSku($row->sku)
            ->withPrice($row->price)
            ->withActive($row->active);
    }
}
