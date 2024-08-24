<?php

declare(strict_types=1);

namespace App\UseCases\Product\Import;

use App\Repository\ProductRepositoryInterface;
use App\UseCases\UseCaseInterface;
use InvalidArgumentException;
use RuntimeException;

use function pathinfo;
use function realpath;

use const PATHINFO_EXTENSION;

readonly class ImportProductUseCase implements UseCaseInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * @param ImportProductUseCaseInput|null $input
     * @return ImportProductUseCaseOutput
     */
    public function handle(ImportProductUseCaseInput $input = null): ImportProductUseCaseOutput
    {
        if (!$input) {
            throw new InvalidArgumentException();
        }

        $file = realpath($input->file);
        if ($file === false) {
            throw new RuntimeException("Arquivo inexistente: {$input->file}");
        }

        $strategy = $this->getStrategyForFile($file);

        return $strategy->handle($input);
    }

    private function getStrategyForFile(string $file): ImportStrategy
    {
        // Poderíamos ter inclusive um formato de descoberta ou registro de plugins aqui
        // para não violar o Open-Closed Principle
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        return match ($extension) {
            'csv' => new CsvImportStrategy($this->productRepository),
            // 'xls' => new XlsxImportStrategy($this->productRepository),
            // 'xlsx' => new XlsxImportStrategy($this->productRepository),
            default => throw new RuntimeException('Extensão inválida'),
        };
    }
}
