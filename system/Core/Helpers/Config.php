<?php

declare(strict_types=1);

namespace System\Core\Helpers;

class Config
{
    private array|false $config;

    public function __construct() {
        $this->config = parse_ini_file(BASE_DIR . '/application/config/.env');
    }

    /**
     * @param $var
     * @return mixed|void
     */
    public static function get($var)
    {
        foreach((new Config)->returnArray() as $key => $value){
            if($var == $key){
                return $value;
            }
        }
    }

    private function returnArray(): array
    {
        return $this->config;
    }

}