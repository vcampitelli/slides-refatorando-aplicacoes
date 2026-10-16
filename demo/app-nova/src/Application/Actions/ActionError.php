<?php

declare(strict_types=1);

namespace App\Application\Actions;

use JsonSerializable;
use ReturnTypeWillChange;

class ActionError implements JsonSerializable
{
    public const string BAD_REQUEST = 'BAD_REQUEST';
    public const string INSUFFICIENT_PRIVILEGES = 'INSUFFICIENT_PRIVILEGES';
    public const string NOT_ALLOWED = 'NOT_ALLOWED';
    public const string NOT_IMPLEMENTED = 'NOT_IMPLEMENTED';
    public const string RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    public const string SERVER_ERROR = 'SERVER_ERROR';
    public const string UNAUTHENTICATED = 'UNAUTHENTICATED';
    public const string VALIDATION_ERROR = 'VALIDATION_ERROR';
    public const string VERIFICATION_ERROR = 'VERIFICATION_ERROR';

    private string $type;

    private ?string $description;

    /** @var list<array<string, mixed>>|null */
    private ?array $trace = null;

    public function __construct(string $type, ?string $description = null)
    {
        $this->type = $type;
        $this->description = $description;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description = null): self
    {
        $this->description = $description;
        return $this;
    }

    /** @param list<array<string, mixed>> $trace */
    public function setTrace(array $trace): self
    {
        $this->trace = $trace;
        return $this;
    }

    /** @return array<string, mixed> */
    #[ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        $return = [
            'type' => $this->type,
            'description' => $this->description,
        ];
        if (!empty($this->trace)) {
            $return['trace'] = $this->trace;
        }
        return $return;
    }
}
