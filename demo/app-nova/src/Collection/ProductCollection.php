<?php

declare(strict_types=1);

namespace App\Collection;

use App\Models\Product;
use ArrayIterator;
use InvalidArgumentException;
use Traversable;

/**
 * @implements CollectionInterface<Product>
 */
class ProductCollection implements CollectionInterface
{
    /**
     * @var Product[]
     */
    private array $products = [];

    public function __construct(Product ...$products)
    {
        $this->products = $products;
    }

    public function add(object $object): void
    {
        if (!$object instanceof Product) {
            throw new InvalidArgumentException(
                'Apenas objetos do tipo ' . Product::class . ' são aceitos'
            );
        }
        $this->products[] = $object;
    }

    /**
     * @return ArrayIterator<int, Product>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->products);
    }

    public function jsonSerialize(): mixed
    {
        return $this->products;
    }
}
