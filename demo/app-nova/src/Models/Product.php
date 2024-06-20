<?php

declare(strict_types=1);

namespace App\Models;

use InvalidArgumentException;

use function strlen;
use function trim;

readonly class Product implements ModelInterface
{
    public ?int $id;
    public int $idCategory;
    public string $name;
    public string $sku;
    public float $price;
    public bool $active;

    /**
     * @param int|null $id
     * @param int $idCategory
     * @param string $name
     * @param string $sku
     * @param float $price
     * @param bool $active
     */
    public function __construct(
        ?int $id,
        int $idCategory,
        string $name,
        string $sku,
        float $price,
        bool $active
    ) {
        if ($idCategory < 1) {
            throw new InvalidArgumentException('ID da categoria inválido');
        }

        $name = trim($name);
        if (empty($name)) {
            throw new InvalidArgumentException('Nome do produto não informado');
        }
        if (strlen($name) < 3) {
            throw new InvalidArgumentException('O nome do produto deve conter ao menos 3 letras');
        }
        if (strlen($name) > 30) {
            throw new InvalidArgumentException('O nome do produto deve conter até 30 letras');
        }

        $sku = trim($sku);
        if (empty($sku)) {
            throw new InvalidArgumentException('SKU não informado');
        }
        if (strlen($sku) != 10) {
            throw new InvalidArgumentException('O SKU deve conter 10 dígitos');
        }

        if ($price <= 0) {
            throw new InvalidArgumentException('Preço inválido');
        }

        $this->id = $id;
        $this->idCategory = $idCategory;
        $this->name = $name;
        $this->sku = $sku;
        $this->price = $price;
        $this->active = $active;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'id_category' => $this->idCategory,
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $this->price,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function withId(?int $id): ModelInterface
    {
        return new Product(
            id: $id,
            idCategory: $this->idCategory,
            name: $this->name,
            sku: $this->sku,
            price: $this->price,
            active: $this->active,
        );
    }
}
