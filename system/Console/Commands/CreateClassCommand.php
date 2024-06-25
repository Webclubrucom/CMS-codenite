<?php

declare(strict_types=1);

namespace System\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use System\Console\Generators\GeneratorClassTrait;

#[AsCommand(name: 'add:class', description: 'Create a class file')]
class CreateClassCommand extends Command
{
    use GeneratorClassTrait;

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Введите название класса?')
            ->addArgument('type', InputArgument::OPTIONAL, 'Введите тип класса?')
            ->addArgument('module', InputArgument::OPTIONAL, 'Введите название модуля?');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $type = $input->getArgument('type');
        $module = $input->getArgument('module');

        $name = ucfirst($name);
        ($type) ? ($type = ucfirst($type)) : $type = 'Controller';
        ($module) ? ($module = ucfirst($module)) : $module = null;

        $content = $this->generatorBodyClass($name, $type, $module);

        return $this->createFileClass($io, $content, $name, $type, $module);

    }
}