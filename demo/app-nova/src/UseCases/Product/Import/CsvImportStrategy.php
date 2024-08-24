<?php

declare(strict_types=1);

namespace App\UseCases\Product\Import;

use App\Models\Product;
use App\Repository\ProductRepositoryInterface;
use RuntimeException;
use Throwable;

readonly class CsvImportStrategy implements ImportStrategy
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * @param ImportProductUseCaseInput $input
     * @return ImportProductUseCaseOutput
     */
    public function handle(ImportProductUseCaseInput $input = null): ImportProductUseCaseOutput
    {
        $handler = fopen($input->file, 'r');
        if ($handler === false) {
            throw new RuntimeException('Erro ao abrir arquivo');
        }

        // Executing before callback
        $before = ($input->before)();

        // Saving other callbacks
        $during = $input->during;
        $error = $input->error;

        $total = 0;
        $success = 0;
        while (($row = fgetcsv($handler, 1000, ";")) !== false) {
            $during($before);
            $total++;

            $product = $this->processRow($row, $error, $total);
            if ($product === false) {
                continue;
            }

            try {
                $this->productRepository->save($product);
            } catch (Throwable $t) {
                $error("Linha {$total}: {$t->getMessage()}");
                continue;
            }

            $success++;
        }

        // Executing after callback
        ($input->after)($before);

        fclose($handler);

        return new ImportProductUseCaseOutput(
            error: $total - $success,
            success: $success,
        );
    }

    /**
     * @param array $row
     * @param \Closure $error
     * @param int $linha
     * @return Product|false
     */
    protected function processRow(array $row, \Closure $error, int $linha): Product|false
    {
        [$idCategory, $name, $sku, $price, $active] = $row + [null, null, null, null, true];

        try {
            $idCategory = (int) $idCategory;
            $name = (string) $name;
            $sku = (string) $sku;
            $price = (float) $price;
            $active = (bool) $active;
            if (empty($sku)) {
                throw new RuntimeException('SKU não informado');
            }

            $product = $this->productRepository->findBySku($sku);
            if (!$product) {
                return new Product(
                    id: null,
                    idCategory: $idCategory,
                    name: $name,
                    sku: $sku,
                    price: $price,
                    active: $active,
                );
            }

            return $product
                ->withIdCategory($idCategory)
                ->withName($name)
                ->withSku($sku)
                ->withPrice($price)
                ->withActive($active);
        } catch (\Throwable $t) {
            $error("Linha {$linha}: {$t->getMessage()}");
            return false;
        }
    }
}
