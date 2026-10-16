<?php

declare(strict_types=1);

namespace App\Application\Actions;

use JsonSerializable;
use ReturnTypeWillChange;

class ActionPayload implements JsonSerializable
{
    private int $statusCode;

    /**
     * @var array<array-key, mixed>|object|null
     */
    private $data;

    private ?ActionError $error;

    /** @param array<array-key, mixed>|object|null $data */
    public function __construct(
        int $statusCode = 200,
        object|array|null $data = null,
        ?ActionError $error = null
    ) {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->error = $error;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array<array-key, mixed>|null|object
     */
    public function getData(): object|array|null
    {
        return $this->data;
    }

    public function getError(): ?ActionError
    {
        return $this->error;
    }

    /** @return array<string, mixed> */
    #[ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        $payload = [
            'statusCode' => $this->statusCode,
        ];

        if ($this->data !== null) {
            $payload['data'] = $this->data;
        } elseif ($this->error !== null) {
            $payload['error'] = $this->error;
        }

        return $payload;
    }
}
