<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\UseCases\Product\Import\ImportProductUseCase;
use App\UseCases\Product\Import\ImportProductUseCaseInput;
use App\UseCases\Product\Import\ImportProductUseCaseOutput;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'product:import',
    description: 'Importa produtos a partir de um arquivo CSV ou XLS'
)]
class ImportProductsCommand extends Command
{
    public function __construct(
        private readonly ImportProductUseCase $useCase,
        ?string $name = null,
    ) {
        parent::__construct($name);
    }

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
        $file = $input->getArgument('file');
        $io = new SymfonyStyle($input, $output);

        $io->title('Importador de produtos');
        $io->text("Lendo arquivo {$file}...");

        $result = $this->useCase->handle(
            new ImportProductUseCaseInput(
                file: $file,
                before: fn() => $io->createProgressBar(),
                during: fn(ProgressBar $progress) => $progress->advance(),
                after: fn(ProgressBar $progress) => $progress->finish(),
                error: $io->error(...),
            ),
        );

        $io->newLine(2);
        $this->displayResult($result, $io);

        return Command::SUCCESS;
    }

    /**
     * @param ImportProductUseCaseOutput $output
     * @param SymfonyStyle $io
     * @return void
     */
    protected function displayResult(ImportProductUseCaseOutput $output, SymfonyStyle $io): void
    {
        $this->displayResultSuccess($output, $io);
        if ($output->error > 0) {
            $io->error("{$output->error} produtos não foram importados por causa de erros");
        }
    }

    /**
     * @param ImportProductUseCaseOutput $output
     * @param SymfonyStyle $io
     * @return void
     */
    private function displayResultSuccess(ImportProductUseCaseOutput $output, SymfonyStyle $io): void
    {
        if ($output->success > 0) {
            $io->success("{$output->success} produtos importados com sucesso");
            return;
        }

        $io->error('Nenhum produto foi importado');
    }
}
