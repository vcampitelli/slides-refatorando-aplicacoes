<?php

declare(strict_types=1);

namespace App\Controllers\Product;

use App\Controllers\AbstractController;
use App\UseCases\Product\CreateProductUseCase;
use App\UseCases\Product\CreateProductUseCaseInput;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

readonly class CreateProductController extends AbstractController
{
    public function __construct(private CreateProductUseCase $useCase)
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

        if (!isset($body['id_category'])) {
            throw new HttpBadRequestException($request, 'Categoria não informada');
        }
        if (!isset($body['name'])) {
            throw new HttpBadRequestException($request, 'Nome não informado');
        }
        if (!isset($body['sku'])) {
            throw new HttpBadRequestException($request, 'SKU não informado');
        }
        if (!isset($body['price'])) {
            throw new HttpBadRequestException($request, 'Preço não informado');
        }

        $output = $this->useCase->handle(
            new CreateProductUseCaseInput(
                idCategory: (int) $body['id_category'],
                name: $body['name'],
                sku: $body['sku'],
                price: (float) $body['price'],
                active: true,
            )
        );

        return $this->response($response, $output->product);
    }
}
