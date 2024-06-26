<?php

declare(strict_types=1);

namespace System\Core\Helpers;

class SearchClass
{
    public static function get($handler): false|int|string
    {
        $classes = self::globClasses(BASE_DIR.'/application');

        return array_search($handler, $classes);
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
                    $out[$nsClass] = basename($nsClass);
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