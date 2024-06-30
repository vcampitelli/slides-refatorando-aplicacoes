<?php

declare(strict_types=1);

namespace App\UseCases\Product;

readonly class ViewProductUseCaseInput
{
    public function __construct(public int $id)
    {
    }
}
