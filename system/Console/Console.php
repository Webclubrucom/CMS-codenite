<?php

declare(strict_types=1);

namespace System\Console;

use DirectoryIterator;
use ReflectionException;
use Symfony\Component\Console\Application;

class Console extends Application
{
    /**
     * @throws ReflectionException
     */
    public function __construct(
        string $name = 'UNKNOWN', string $version = 'UNKNOWN'
    ) {
        parent::__construct($name, $version);
        $this->registerCommands();
    }

    /**
     * @throws ReflectionException
     */
    private function registerCommands(): void
    {

        $commandFilesPath = __DIR__.'\Commands';
        $namespace = 'System\\Console\\Commands\\';

        foreach (new DirectoryIterator($commandFilesPath) as $commandFile) {
            if (! $commandFile->isFile()) {
                continue;
            }
            $command = $namespace.$commandFile->getBasename('.php');
            $this->add(new $command());
        }

    }

}
