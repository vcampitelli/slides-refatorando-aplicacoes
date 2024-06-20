<?php

declare(strict_types=1);

use App\Controllers\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/products', function (Group $group) {
        $group->get('', Product\ListProductController::class);
        $group->get('/{id}', Product\ViewProductController::class);
        $group->post('', Product\CreateProductController::class);
        $group->post('/bulk', Product\BulkCreateProductController::class);
    });
};
