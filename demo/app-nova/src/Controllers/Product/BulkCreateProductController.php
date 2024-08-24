<?php

declare(strict_types=1);

namespace App\Controllers\Product;

use App\Controllers\AbstractController;
use App\UseCases\Product\BulkCreate\BulkCreateProductUseCase;
use App\UseCases\Product\BulkCreate\BulkCreateProductUseCaseInput;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class BulkCreateProductController extends AbstractController
{
    public function __construct(private BulkCreateProductUseCase $useCase)
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
        $errors = [];
        $body = $request->getParsedBody();

        $addError = function (int $index, string $message) use (&$errors) {
            if (!isset($errors[$index])) {
                $errors[$index] = [
                    'line' => $index + 1,
                    'errors' => [],
                ];
            }
            $errors[$index]['errors'][] = $message;
        };

        $input = new BulkCreateProductUseCaseInput();
        foreach ($body['products'] as $i => $row) {
            if (empty($row['id_category'])) {
                $addError($i, 'ID da categoria não informado');
                continue;
            }

            if (empty($row['name'])) {
                $addError($i, 'Nome do produto não informado');
                continue;
            }

            if (empty($row['sku'])) {
                $addError($i, 'SKU não informado');
                continue;
            }

            if (empty($row['price'])) {
                $addError($i, 'Preço não informado');
                continue;
            }

            $input->add(
                (int) $row['id_category'],
                $row['name'],
                $row['sku'],
                (float) $row['price'],
                (bool) ($row['active'] ?? true),
            );
        }

        $output = $this->useCase->handle($input);

        return $this->response($response, [
            'success' => $output->count,
            'errors' => \array_merge(
                $output->errors->getIterator()->getArrayCopy(),
                $errors
            ),
        ]);
    }
}
