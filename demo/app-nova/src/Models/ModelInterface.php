<?php

declare(strict_types=1);

namespace App\Models;

use JsonSerializable;

interface ModelInterface extends JsonSerializable
{
    public function getId(): ?int;
    public function withId(?int $id): ModelInterface;
}
