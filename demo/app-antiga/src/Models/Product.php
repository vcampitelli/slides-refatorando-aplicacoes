<?php

declare(strict_types=1);

namespace App\Models;

class Product extends AbstractModel
{
    private int $id;
    private int $idCategory;
    private string $name;
    private float $price;

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdCategory(): ?int
    {
        return $this->idCategory ?? null;
    }

    public function setIdCategory(int $idCategory): void
    {
        $this->idCategory = $idCategory;
    }

    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): ?float
    {
        return $this->price ?? null;
    }

    public function setPrice($price): void
    {
        $this->price = (float) $price;
    }

    public function isActive(): ?bool
    {
        return $this->active ?? null;
    }

    public function setActive($active): void
    {
        $this->active = (bool) $active;
    }
    private bool $active;

    protected function getTableName(): string
    {
        return 'products';
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'id_category' => $this->getIdCategory(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
        ];
    }
}
