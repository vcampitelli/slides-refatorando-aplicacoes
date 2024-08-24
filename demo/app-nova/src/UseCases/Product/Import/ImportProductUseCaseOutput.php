<?php

declare(strict_types=1);

namespace App\UseCases\Product\Import;

readonly class ImportProductUseCaseOutput
{
    public function __construct(
        public int $error,
        public int $success,
    ) {
    }
}
