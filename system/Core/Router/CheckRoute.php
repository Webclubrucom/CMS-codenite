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
            $file_content = '<?php'.PHP_EOL.PHP_EOL.'use System\Core\Router\Facades\Route;'.PHP_EOL.PHP_EOL.'return [';

            foreach (glob(BASE_DIR.'/application/Modules/*/Routes/routes.php') as $filename) {
                $content = str_replace('<?php', '', trim(file_get_contents($filename)));
                $content = str_replace(';', ',', trim($content));
                $file_content .= PHP_EOL.'    '.$content;
            }

            $file_content .= PHP_EOL.'];';

            if (! is_dir(BASE_DIR.Config::get('PATH_CACHE'))) {
                mkdir(BASE_DIR.Config::get('PATH_CACHE'), 0777, true);
            }
            file_put_contents($routes, $file_content, FILE_APPEND | LOCK_EX);
        }
    }
}
