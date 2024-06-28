<?php

declare(strict_types=1);

namespace System\Core\Router;

use System\Core\Helpers\Config;

class CheckRoute
{
    public string $routes;

    public function __construct(string $routes)
    {

        $this->availabilityRoute($routes);
    }

    public function availabilityRoute(string $routes): string
    {
        if (! is_file($routes)) {
            $this->creatingRoute($routes);
        }

        return $this->routes = $routes;
    }

    private function creatingRoute(string $routes): void
    {
        if (is_dir(BASE_DIR.'/application/Modules')) {
            $file_content_start = '<?php'.PHP_EOL.PHP_EOL.'use System\Core\Router\Facades\Route;'.PHP_EOL.PHP_EOL.'return [';
            $file_content = '';
            $file_content_end = PHP_EOL.'];';
            $arrRoutes = $this->recursiveGlob(BASE_DIR.'/application/.routes');
            foreach ($arrRoutes as $filename) {
                $content = str_replace('<?php', '', trim(file_get_contents($filename)));
                $content = str_replace(';', ',', trim($content));
                $file_content .= PHP_EOL.$content;
            }
            $fileRoutes = $file_content_start;
            $file_content = str_replace("\n", "\n\t", $file_content);
            $fileRoutes .= $file_content;
            $fileRoutes .= $file_content_end;

            if (! is_dir(BASE_DIR.Config::get('PATH_CACHE'))) {
                mkdir(BASE_DIR.Config::get('PATH_CACHE'), 0777, true);
            }
            file_put_contents($routes, $fileRoutes, FILE_APPEND | LOCK_EX);
        }
    }

    private function recursiveGlob($pattern): false|array
    {
        $files = glob($pattern, 0);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge(
                [],
                ...[$files, $this->recursiveGlob($dir.'/'.basename($pattern))]
            );
        }

        return $files;
    }
}
