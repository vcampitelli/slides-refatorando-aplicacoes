<?php

declare(strict_types=1);

namespace App\Controllers\Product;

use App\Controllers\AbstractController;
use App\Models\Product;
use App\Repository\ProductRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

readonly class ViewProductController extends AbstractController
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * Retorna os dados de um produto
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $product = $this->productRepository->findById((int) $args['id']);

        return $this->json($response, $product, ($product === null) ? 404 : 200);
    }
}
