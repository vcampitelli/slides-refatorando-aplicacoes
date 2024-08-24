<?php

declare(strict_types=1);

namespace App\UseCases\Product\BulkCreate;

readonly class BulkCreateProductUseCaseOutput
{
    public function __construct(
        public int $count,
        public BulkCreateProductUseCaseOutputErrors $errors
    ) {
    }
}
