<?php

declare(strict_types=1);

namespace App\UseCases\Product\Import;

use Closure;

readonly class ImportProductUseCaseInput
{
    /**
     * @param string $file
     * @param Closure():mixed $before
     * @param Closure(mixed):void $during
     * @param Closure(mixed):mixed $after
     * @param Closure(string):void $error
     */
    public function __construct(
        public string $file,
        public Closure $before,
        public Closure $during,
        public Closure $after,
        public Closure $error,
    ) {
    }
}
