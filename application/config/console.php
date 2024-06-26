<?php

use Doctrine\DBAL\Connection as Dbal;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use System\Console\Commands\MigrateCommand;
use System\Console\Console;
use System\Core\Database\Connection;
use System\Core\Helpers\Config;

$container = new Container();
$container->defaultToShared(true);
$container->delegate(new ReflectionContainer(true));

$container->add('console', Console::class)->addArgument($container);

$container->add('connection', Connection::class);
$container->addShared('db', concrete: function () use ($container): Dbal {
    return $container->get('connection')->create();
});

$container->add(MigrateCommand::class)
    ->addArgument('db')
    ->addArgument(new StringArgument(Config::get('PATH_MIGRATIONS')));

return $container;
