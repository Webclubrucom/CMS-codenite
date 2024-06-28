<?php

declare(strict_types=1);

namespace System\Core\Helpers;

class SearchClass
{
    const string PATH_CLASSES = BASE_DIR.'/system/cache/classes.php';
    public static function get($handler): false|int|string
    {
        if (! is_file(self::PATH_CLASSES)) {
            self::createFileClasses();
        }

        $classes = include self::PATH_CLASSES;

        return array_search($handler, $classes);
    }

    private static function createFileClasses(): void
    {
        $file_content_start = '<?php'.PHP_EOL.PHP_EOL.'return ';
        $file_content_end = ';';
        $arrClasses = self::globClasses(BASE_DIR.'/application');
        $textArray = var_export($arrClasses, true);
        $textArray = str_replace('\\\\', '\\', $textArray);

        $fileRoutes = $file_content_start;
        $fileRoutes .= $textArray;
        $fileRoutes .= $file_content_end;

        if (! is_dir(BASE_DIR.Config::get('PATH_CACHE'))) {
            mkdir(BASE_DIR.Config::get('PATH_CACHE'), 0777, true);
        }
        file_put_contents(self::PATH_CLASSES, $fileRoutes, FILE_APPEND | LOCK_EX);
    }

    private static function globClasses(string $path): array
    {
        $out = [];
        foreach (glob($path.'/*') as $file) {
            if (is_dir($file)) {
                $out = array_merge($out, self::globClasses($file));
            } else {
                if (self::getExtension($file) === 'php') {
                    $nsClass = self::convertingStringClass($file);
                    if (class_exists($nsClass)) {
                        $out[$nsClass] = basename($nsClass);
                    }
                }
            }
        }

        return $out;
    }

    private static function convertingStringClass($file): array|string
    {
        $nsClass = substr($file, 0, strrpos($file, '.'));
        $nsClass = str_replace(BASE_DIR, '', $nsClass);
        $str = self::getStringBetween($nsClass);
        $nsClass = str_replace($str, ucfirst($str), $nsClass);

        return str_replace('/', '\\', $nsClass);
    }

    private static function getExtension($filename): string
    {
        return substr(strrchr($filename, '.'), 1);
    }

    private static function getStringBetween($string): string
    {
        $string = ' '.$string;
        $ini = strpos($string, '/');
        if ($ini == 0) {
            return '';
        }
        $ini += strlen('/');
        $len = strpos($string, '/', $ini) - $ini;

        return substr($string, $ini, $len);
    }
}
