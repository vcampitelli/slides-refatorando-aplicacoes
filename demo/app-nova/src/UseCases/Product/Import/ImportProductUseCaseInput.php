<?php

declare(strict_types=1);

namespace App\UseCases\Product\Import;

use Closure;

/** @template T */
readonly class ImportProductUseCaseInput
{
    /**
     * @param string $file
     * @param Closure():T $before
     * @param Closure(T):void $during
     * @param Closure(T):mixed $after
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
