<?php

declare(strict_types=1);

namespace App\Application\Controllers\Product;

use App\Application\Controllers\AbstractController;
use App\Models\Product;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ProductController extends AbstractController
{
    /**
     * Lista os produtos
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function list(Request $request, Response $response): Response
    {
        $products = Product::findAll();

        return $this->json($response, $products);
    }

    /**
     * Retorna os dados de um produto
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function get(Request $request, Response $response, array $args): Response
    {
        $product = Product::find($args['id']);

        return $this->json($response, $product, ($product === null) ? 404 : 200);
    }

    /**
     * Cadastra um produto
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        if (!isset($body['id_category'])) {
            throw new \Exception('ID da categoria não informado');
        }
        $body['id_category'] = (int) $body['id_category'];
        if ($body['id_category'] < 1) {
            throw new \Exception('ID da categoria inválido');
        }

        if (empty($body['name'])) {
            throw new \Exception('Nome do produto não informado');
        }
        if (\strlen($body['name']) < 3) {
            throw new \Exception('O nome do produto deve conter ao menos 3 letras');
        }
        if (\strlen($body['name']) > 20) {
            throw new \Exception('O nome do produto deve conter até 20 letras');
        }

        if (!isset($body['price'])) {
            throw new \Exception('Preço não informado');
        }
        $body['price'] = (float) $body['price'];
        if ($body['price'] <= 0) {
            throw new \Exception('Preço inválido');
        }

        $product = new Product();
        $product->setIdCategory($body['id_category']);
        $product->setName($body['name']);
        $product->setPrice($body['price']);
        $product->setActive(true);
        $product->save();

        return $this->json($response, $product);
    }

    /**
     * Importa produtos em lote
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function import(Request $request, Response $response): Response
    {
    }
}
