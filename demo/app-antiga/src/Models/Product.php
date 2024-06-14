<?php

declare(strict_types=1);

namespace App\Models;

class Product extends AbstractModel
{
    private int $id;
    private int $idCategory;
    private string $name;
    private string $sku;
    private float $price;
    private bool $active;

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

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    protected function getTableName(): string
    {
        return 'products';
    }

    public static function findBySku(string $sku): ?Product
    {
        $baseModel = static::getInstance();
        $database = $baseModel->getDatabase();

        $model = null;
        $database->select('products', '*', ['sku' => $sku], function ($row) use ($baseModel, &$model) {
            $model = $baseModel->model($row);
        });
        return $model;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'id_category' => $this->getIdCategory(),
            'name' => $this->getName(),
            'sku' => $this->getSku(),
            'price' => $this->getPrice(),
        ];
    }
}
