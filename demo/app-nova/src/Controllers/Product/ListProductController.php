<?php

declare(strict_types=1);

namespace App\Controllers\Product;

use App\Controllers\AbstractController;
use App\Models\Product;
use App\Repository\ProductRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

readonly class ListProductController extends AbstractController
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * Lista os produtos
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $products = $this->productRepository->findAll();

        return $this->json($response, $products);
    }
}
