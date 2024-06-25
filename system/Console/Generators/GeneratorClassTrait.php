<?php

declare(strict_types=1);

namespace System\Console\Generators;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

trait GeneratorClassTrait
{
    private string $startPhp = '<?php'.PHP_EOL.PHP_EOL.'declare(strict_types=1);'.PHP_EOL.PHP_EOL;

    private string $namespace = 'namespace Application';

    private array $types = [
        'Controller' => 'System\\Abstracts\\AbstractHandler',
        'Handler' => 'System\\Abstracts\\AbstractHandler',
        'Service' => 'System\\Abstracts\\AbstractService',
        'Model' => 'System\\Abstracts\\AbstractModel',
        'Event' => 'System\\Abstracts\\AbstractEvent',
        'Factory' => 'System\\Abstracts\\AbstractFactory',
        'Listener' => 'System\\Abstracts\\AbstractListener',
        'Middleware' => 'System\\Abstracts\\AbstractMiddleware',
        'Provider' => 'System\\Abstracts\\AbstractProvider',
        'Exception' => 'Exception',
    ];

    private array $intersect = [
        'Controller' => 'System\\Abstracts\\AbstractHandler',
        'Handler' => 'System\\Abstracts\\AbstractHandler',
        'Service' => 'System\\Abstracts\\AbstractService',
        'Model' => 'System\\Abstracts\\AbstractModel',
    ];

    public function generatorBodyClass(string $name, ?string $type = 'Controller', ?string $module = null): string
    {
        $final = (array_intersect_key($this->types, $this->intersect)) ? 'final ' : '';
        if ($type) {
            $array = explode('\\', $this->types[$type]);
            $extends = ' extends '.end($array);
        } else {
            $extends = '';
        }


        $body = $this->startPhp;
        $body .= $this->generatorNamespaceClass($type, $module);
        $body .= $this->generatorUseClass($type);
        $body .= $final.'class '.$name.$type.$extends.PHP_EOL.'{'.PHP_EOL.PHP_EOL;
        $body .= '}';

        return $body;
    }

    public function generatorNamespaceClass(?string $type = null, ?string $module = null): string
    {
        if ($type && $module) {
            $this->namespace = $this->namespace.'\\Modules\\'.$module.'\\'.$type.';'.PHP_EOL.PHP_EOL;
        } else {
            $this->namespace = $this->namespace.'\\'.$type.';'.PHP_EOL.PHP_EOL;
        }

        return $this->namespace;
    }

    public function generatorUseClass(?string $type = null): string
    {
        $useNamespace = '';
        if ($type) {
            $useNamespace = 'use '.$this->types[$type].';'.PHP_EOL.PHP_EOL;
        }

        return $useNamespace;
    }

    public function createFileClass(SymfonyStyle $io, string $contents, string $name, ?string $type = 'Controller', ?string $module = null): int
    {
        if ($type && $module) {
            $path = '/application/Modules/'.$module.'/'.$type;
        } else {
            $path = '/application/'.$type;
        }

        if (! is_dir(BASE_DIR.$path)) {
            mkdir(BASE_DIR.$path, 0777, true);
        }
        if (! is_file(BASE_DIR.$path.'/'.$name.$type.'.php')) {
            file_put_contents(BASE_DIR.$path.'/'.$name.$type.'.php', $contents);
            $io->success('Файл '.$path.'/'.$name.$type.'.php успешно создан!');

            return Command::SUCCESS;
        } else {
            $io->error('Файл '.$path.'/'.$name.$type.'.php уже существует!');

            return Command::FAILURE;
        }

    }
}
