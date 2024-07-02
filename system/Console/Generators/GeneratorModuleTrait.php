<?php

declare(strict_types=1);

namespace System\Console\Generators;

use Symfony\Component\Console\Command\Command;
use System\Core\Helpers\Config;

trait GeneratorModuleTrait
{
    private array $types = [
        'Controller' => 'System\\Core\\Abstracts\\AbstractController',
        'Handler' => 'System\\Core\\Abstracts\\AbstractController',
        'Service' => 'System\\Core\\Abstracts\\AbstractService',
        'Model' => 'System\\Core\\Abstracts\\AbstractModel',
        'Entity' => 'System\\Core\\Abstracts\\AbstractEntity',
        'Event' => 'System\\Core\\Abstracts\\AbstractEvent',
        'Factory' => 'System\\Core\\Abstracts\\AbstractFactory',
        'Listener' => 'System\\Core\\Abstracts\\AbstractListener',
        'Middleware' => 'System\\Core\\Abstracts\\AbstractMiddleware',
        'Provider' => 'System\\Core\\Abstracts\\AbstractProvider',
        'Exception' => 'Exception',
    ];

    private array $intersect = [
        'Controller' => 'System\\Core\\Abstracts\\AbstractController',
        'Handler' => 'System\\Core\\Abstracts\\AbstractController',
        'Service' => 'System\\Abstracts\\AbstractService',
        'Model' => 'System\\Abstracts\\AbstractModel',
    ];

    public function createModule($io, $module): int
    {
        $path = BASE_DIR.'/application/Modules';

        if (
            file_exists($path.'/'.$module.'/Controller') &&
            file_exists($path.'/'.$module.'/Routes') &&
            file_exists($path.'/'.$module.'/templates') &&
            file_exists($path.'/'.$module.'/Entities') &&
            file_exists($path.'/'.$module.'/Services')
        ) {
            $io->error('Модуль уже существует!');

            return Command::FAILURE;
        }

        /**
         * Создание директорий модуля
         */

        if (! file_exists($path)) {
            mkdir($path, 0777, true);
        }
        if (! file_exists($path.'/'.$module)) {
            mkdir($path.'/'.$module, 0777, true);
        }

        if (! file_exists($path.'/'.$module.'/Controller')) {
            mkdir($path.'/'.$module.'/Controller', 0777, true);
        }

        if (! file_exists($path.'/'.$module.'/Routes')) {
            mkdir($path.'/'.$module.'/Routes', 0777, true);
        }

        if (! file_exists($path.'/'.$module.'/templates')) {
            mkdir($path.'/'.$module.'/templates', 0777, true);
        }

        if (! file_exists($path.'/'.$module.'/Entity')) {
            mkdir($path.'/'.$module.'/Entity', 0777, true);
        }

        if (! file_exists($path.'/'.$module.'/Service')) {
            mkdir($path.'/'.$module.'/Service', 0777, true);
        }

        /**
         * Создание файлов модуля
         */

        if (! is_file($path.'/'.$module.'/Routes/.routes')) {
            file_put_contents($path.'/'.$module.'/Routes/.routes',
                "Route::get('/".mb_strtolower($module)."', '".$module."Controller@index');");
        }

        if (! is_file($path.'/'.$module.'/templates/'.mb_strtolower($module).'.html.twig')) {
            file_put_contents($path.'/'.$module.'/templates/'.mb_strtolower($module).'.html.twig',
                '{{ title }}');
        }

        if (! is_file($path.'/'.$module.'/Controller/'.$module.'Controller.php')) {
            file_put_contents($path.'/'.$module.'/Controller/'.$module.'Controller.php',
                $this->content('Controller', $module));
        }

        if (! is_file($path.'/'.$module.'/Entity/'.$module.'Entity.php')) {
            file_put_contents($path.'/'.$module.'/Entity/'.$module.'Entity.php',
                $this->content('Entity', $module));
        }

        if (! is_file($path.'/'.$module.'/Service/'.$module.'Service.php')) {
            file_put_contents($path.'/'.$module.'/Service/'.$module.'Service.php',
                $this->content('Service', $module));
        }

        $this->deleteRoutesClassesFile();

        $io->success('Модуль успешно создан!');

        return Command::SUCCESS;
    }

    private function content(string $type, string $module): string
    {
        $namespase = 'namespace Application\\Modules\\'.$module.'\\'.$type.';';
        return '<?php'.PHP_EOL.PHP_EOL.
            $namespase.PHP_EOL.PHP_EOL.
            $this->generatorUseClass($type).
            'class '.$module.$type.$this->extends($type).PHP_EOL.'{'.
            PHP_EOL.
            $this->method($type, $module).
            PHP_EOL.
            '}';
    }

    public function extends(?string $type = null): string
    {
        if ($this->types[$type]) {
            $array = explode('\\', $this->types[$type]);
            return ' extends '.end($array);
        }

        return '';
    }

    private function method(string $type, string $module): string
    {
        if ($type == 'Controller') {
            return "\tpublic function index()".PHP_EOL.
                "\t{".PHP_EOL.
                "\t\t\$data = ['title' => '".$module."'];".PHP_EOL.PHP_EOL.
                "\t\treturn \$this->render('Modules/".$module."/templates/".mb_strtolower($module)."', \$data);".PHP_EOL.
                "\t}
        ";
        }
        return '';
    }

    public function generatorUseClass(?string $type = null): string
    {
        $useNamespace = '';
        if ($this->types[$type]) {
            $useNamespace = 'use '.$this->types[$type].';'.PHP_EOL.PHP_EOL;
        }

        return $useNamespace;
    }

    private function deleteRoutesClassesFile(): void
    {
        $routes = BASE_DIR.Config::get('PATH_CACHE').'/routes.php';
        $classes = BASE_DIR.'/system/cache/classes.php';
        if (is_file($routes)) {
            unlink($routes);
        }
        if (is_file($classes)) {
            unlink($classes);
        }

    }

}