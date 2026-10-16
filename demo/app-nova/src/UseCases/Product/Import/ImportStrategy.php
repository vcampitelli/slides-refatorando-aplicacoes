<?php

declare(strict_types=1);

namespace App\UseCases\Product\Import;

interface ImportStrategy
{
    /**
     * @template T
     * @param ImportProductUseCaseInput<T> $input
     * @return ImportProductUseCaseOutput
     */
    public function handle(ImportProductUseCaseInput $input): ImportProductUseCaseOutput;
}
