<?php

declare(strict_types=1);

namespace App\Application\Actions\Product;

use App\Application\Actions\AbstractAction;
use App\UseCases\Product\ViewProductUseCase;
use App\UseCases\Product\ViewProductUseCaseInput;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class ViewProductAction extends AbstractAction
{
    public function __construct(private ViewProductUseCase $useCase)
    {
    }

    /**
     * Retorna os dados de um produto
     *
     * @param Request $request
     * @param Response $response
     * @param array<string, string> $args
     * @return Response
     *
     * @SuppressWarnings("PHPMD.UnusedFormalParameter")
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $output = $this->useCase->handle(
            new ViewProductUseCaseInput((int) $args['id'])
        );

        return $this->response(
            $response,
            $output->product,
            ($output->product === null) ? 404 : 200
        );
    }
}
