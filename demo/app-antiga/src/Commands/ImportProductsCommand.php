<?php

declare(strict_types=1);

namespace App\Commands;

use App\Models\Product;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'product:import',
    description: 'Importa produtos a partir de um arquivo CSV ou XLS'
)]
class ImportProductsCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument(
            'file',
            InputArgument::REQUIRED,
            'Caminho do arquivo'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Importador de produtos');
        $file = \realpath($input->getArgument('file'));
        if ($file === false) {
            throw new \Exception("Arquivo inexistente: {$input->getArgument('file')}");
        }
        $io->text("Lendo arquivo {$file}...");

        $extension = \pathinfo($file, PATHINFO_EXTENSION);
        $result = match ($extension) {
            'csv' => $this->importCsv($io, $file),
            'xlsx' => $this->importXlsx($io, $file),
            default => throw new \RuntimeException('Extensão inválida'),
        };

        $io->newLine(2);
        if ($result['success'] > 0) {
            $io->success("{$result['success']} produtos importados com sucesso");
        } else {
            $io->error('Nenhum produto foi importado');
        }
        if ($result['error'] > 0) {
            $io->error("{$result['error']} produtos não foram importados por causa de erros");
        }

        return Command::SUCCESS;
    }

    protected function importCsv(SymfonyStyle $io, string $file): array
    {
        $handler = \fopen($file, 'r');
        if ($handler === false) {
            throw new \RuntimeException('Erro ao abrir arquivo');
        }

        $progress = $io->createProgressBar();

        $total = 0;
        $success = 0;
        while (($row = \fgetcsv($handler, 1000, ";")) !== false) {
            $progress->advance();
            $total++;

            if (!isset($row[0])) {
                $io->error("Linha {$total}: ID da categoria não informado");
                continue;
            }
            $row[0] = (int) $row[0];
            if ($row[0] < 1) {
                $io->error("Linha {$total}: ID da categoria inválido");
                continue;
            }

            if (empty($row[1])) {
                $io->error("Linha {$total}: Nome do produto não informado");
                continue;
            }
            if (\strlen($row[1]) < 3) {
                $io->error("Linha {$total}: O nome do produto deve conter ao menos 3 letras");
                continue;
            }
            if (\strlen($row[1]) > 30) {
                $io->error("Linha {$total}: O nome do produto deve conter até 30 letras");
                continue;
            }

            if (empty($row[2])) {
                $io->error("Linha {$total}: SKU não informado");
                continue;
            }
            if (\strlen($row[2]) < 5) {
                $io->error("Linha {$total}: O SKU deve conter ao menos 5 dígitos");
                continue;
            }

            if (empty($row[3])) {
                $io->error("Linha {$total}: Preço não informado");
                continue;
            }
            $row[3] = (float) $row[3];
            if ($row[3] <= 0) {
                $io->error("Linha {$total}: Preço inválido");
                continue;
            }

            $produto = Product::findBySku($row[2]);
            if (!$produto) {
                $produto = new Product();
                $produto->setSku($row[2]);
            }
            $produto->setIdCategory($row[0]);
            $produto->setName($row[1]);
            $produto->setPrice($row[3]);
            $produto->setActive((bool) $row[4]);
            try {
                $produto->save();
            } catch (\Exception $e) {
                $io->error("Linha {$total}: {$e->getMessage()}");
                continue;
            }

            $success++;
        }

        $progress->finish();
        \fclose($handler);

        return [
            'error' => $total - $success,
            'success' => $success,
        ];
    }

    protected function importXlsx(SymfonyStyle $io, string $file): array
    {
        $total = 0;
        $success = 0;
        return [
            'error' => $total - $success,
            'success' => $success,
        ];
    }
}
