<?php

declare(strict_types=1);

namespace App\Controllers\Product;

use App\Controllers\AbstractController;
use App\Models\Product;
use App\Repository\ProductRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

readonly class BulkCreateProductController extends AbstractController
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * Importa produtos em lote
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $count = 0;
        $errors = [];
        $body = $request->getParsedBody();
        foreach ($body['products'] as $i => $row) {
            if (!isset($errors[$i])) {
                $errors[$i] = [];
            }

            if (!isset($row['id_category'])) {
                $errors[$i][] = 'ID da categoria não informado';
                continue;
            }
            $row['id_category'] = (int) $row['id_category'];
            if ($row['id_category'] < 1) {
                $errors[$i][] = 'ID da categoria inválido';
                continue;
            }

            if (empty($row['name'])) {
                $errors[$i][] = 'Nome do produto não informado';
                continue;
            }
            if (\strlen($row['name']) < 3) {
                $errors[$i][] = 'O nome do produto deve conter ao menos 3 letras';
                continue;
            }
            if (\strlen($row['name']) > 15) {
                $errors[$i][] = 'O nome do produto deve conter até 15 letras';
                continue;
            }

            if (empty($body['sku'])) {
                $errors[$i][] = 'SKU não informado';
                continue;
            }

            if (!isset($row['price'])) {
                $errors[$i][] = 'Preço não informado';
                continue;
            }
            $row['price'] = (float) $row['price'];
            if ($row['price'] <= 0) {
                $errors[$i][] = 'Preço inválido';
                continue;
            }

            try {
                $product = new Product();
                $product->setIdCategory($row['id_category']);
                $product->setName($row['name']);
                $product->setPrice($row['price']);
                $product->setActive((bool) ($row['active'] ?? true));
                $product->save();
                $count++;
            } catch (\Exception $e) {
                $errors[$i][] = $e->getMessage();
            }
        }

        return $this->response($response, [
            'success' => $count,
            'errors' => array_filter($errors),
        ]);
    }
}
