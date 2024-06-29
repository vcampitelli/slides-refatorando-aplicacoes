<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\Product;

readonly class ViewProductUseCaseOutput
{
    public function __construct(public ?Product $product)
    {
    }
}
