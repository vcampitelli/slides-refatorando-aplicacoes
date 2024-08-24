<?php

declare(strict_types=1);

namespace App\UseCases\Product\BulkCreate;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class BulkCreateProductUseCaseOutputErrors implements IteratorAggregate
{
    /**
     * @var array<string>
     */
    private array $errors = [];

    public function add(int $line, string $message): self
    {
        $this->errors[$line] = $message;
        return $this;
    }

    /**
     * @return ArrayIterator<int, string>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->errors);
    }
}
