<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;

use function json_encode;

use const JSON_PRETTY_PRINT;

readonly abstract class AbstractController
{
    /**
     * @param Response $response
     * @param object|array|null $data
     * @param int $statusCode
     * @return Response
     */
    protected function response(Response $response, object|array $data = null, int $statusCode = 200): Response
    {
        $payload = new ActionPayload($statusCode, $data);

        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $response->getBody()->write($json);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($payload->getStatusCode());
    }
}
