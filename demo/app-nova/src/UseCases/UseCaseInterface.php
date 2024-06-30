<?php

declare(strict_types=1);

namespace App\UseCases;

interface UseCaseInterface
{
    public function handle(): object;
}
