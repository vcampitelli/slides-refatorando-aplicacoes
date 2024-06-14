<?php

declare(strict_types=1);

namespace App\Controllers\Product;

use App\Controllers\AbstractController;
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

        $body['name'] = \trim($body['name'] ?? '');
        if (empty($body['name'])) {
            throw new \Exception('Nome do produto não informado');
        }
        if (\strlen($body['name']) < 3) {
            throw new \Exception('O nome do produto deve conter ao menos 3 letras');
        }
        if (\strlen($body['name']) > 30) {
            throw new \Exception('O nome do produto deve conter até 30 letras');
        }

        if (empty($body['sku'])) {
            throw new \Exception('SKU não informado');
        }
        if (\strlen($body['sku']) != 10) {
            throw new \Exception('O SKU deve conter 10 dígitos');
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
    public function bulk(Request $request, Response $response): Response
    {
        $count = 0;
        $errors = [];
        foreach ($request as $i => $row) {
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

        return $this->json($response, [
            'success' => $count,
            'errors' => array_filter($errors),
        ]);
    }
}
