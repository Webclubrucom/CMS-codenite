<?php

declare(strict_types=1);

namespace System\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use System\Console\Generators\GeneratorMigrationTrait;

#[AsCommand(name: 'create:migration', description: 'Создание файла миграции', aliases: ['add:migration'])]
class CreateMigrationCommand extends Command
{
    use GeneratorMigrationTrait;

    protected function configure(): void
    {
        $this->addArgument('nameMigration', InputArgument::REQUIRED, description: 'Введите название миграции');
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('nameMigration');

        return $this->createFileMigration($io, $name);
    }

}
