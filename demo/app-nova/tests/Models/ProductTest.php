<?php

namespace Tests\Models;

use App\Models\Product;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProductTest extends TestCase
{
    #[DataProvider('providerInvalidArguments')]
    /**
     * Test if Product __construct method throws exceptions correctly for invalid inputs
     * @param int|null $id
     * @param int $idCategory
     * @param string $name
     * @param string $sku
     * @param float $price
     * @param bool $active
     * @param string $expectedExceptionMessage
     */
    public function testExceptionWhenInvalidArgumentPassedToConstruct(
        $id,
        $idCategory,
        $name,
        $sku,
        $price,
        $active,
        $expectedExceptionMessage
    ) {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        new Product($id, $idCategory, $name, $sku, $price, $active);
    }

    public static function providerInvalidArguments(): array
    {
        return [
            // test empty product id
            [0, 0, 'product name', 'ABCDEFGHIJ', 10.5, true, 'ID do produto inválido'],
            // test empty category id
            [null, 0, 'product name', 'ABCDEFGHIJ', 10.5, true, 'ID da categoria inválido'],
            // test short product name
            [null, 1, 'pn', 'ABCDEFGHIJ', 10.5, true, 'O nome do produto deve conter ao menos 3 letras'],
            // test long product name
            [null, 1, str_repeat('a', 31), 'ABCDEFGHIJ', 10.5, true, 'O nome do produto deve conter até 30 letras'],
            // test empty SKU
            [null, 1, 'product name', '', 10.5, true, 'SKU não informado'],
            // test short SKU
            [null, 1, 'product name', 'ABCDE', 10.5, true, 'O SKU deve conter 10 dígitos'],
            // test long SKU
            [null, 1, 'product name', 'ABCDEFGHIJKL', 10.5, true, 'O SKU deve conter 10 dígitos'],
            // test invalid price
            [null, 1, 'product name', 'ABCDEFGHIJ', 0, true, 'Preço inválido']
        ];
    }

    /**
     * Test if Product __construct method works as expected
     */
    public function testValidProductInstance()
    {
        $product = new Product(1, 2, 'product name', 'ABCDEFGHIJ', 10.5, true);

        $this->assertSame(1, $product->id);
        $this->assertSame(2, $product->idCategory);
        $this->assertSame('product name', $product->name);
        $this->assertSame('ABCDEFGHIJ', $product->sku);
        $this->assertSame(10.5, $product->price);
        $this->assertTrue($product->active);
    }
}
