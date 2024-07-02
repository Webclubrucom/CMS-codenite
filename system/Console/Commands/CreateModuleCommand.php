<?php

declare(strict_types=1);

namespace System\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use System\Console\Generators\GeneratorModuleTrait;

#[AsCommand(name: 'create:module', description: 'Создание модуля', aliases: ['add:module'])]
class CreateModuleCommand extends Command
{
    use GeneratorModuleTrait;
    protected function configure(): void
    {
        $this->addArgument('nameModule', InputArgument::REQUIRED, description: 'Введите название модуля');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('nameModule');
        if ($name == '') {
            $io->warning('Введите название модуля!');
            $io->info("php codenite add:module <название модуля>");

            return Command::FAILURE;
        }

        $name = ucfirst($name);

        return $this->createModule($io, $name);
    }


}