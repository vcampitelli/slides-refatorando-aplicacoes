<?php

declare(strict_types=1);

namespace App\Controllers\Product;

use App\Controllers\AbstractController;
use App\Models\Product;
use App\Repository\ProductRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

readonly class CreateProductController extends AbstractController
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * Cadastra um produto
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $product = new Product(
            id: null,
            idCategory: $body['id_category'],
            name: $body['name'],
            sku: $body['sku'],
            price: $body['price'],
            active: true,
        );
        $this->productRepository->save($product);

        return $this->response($response, $product);
    }
}
