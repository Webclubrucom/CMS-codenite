<?php

declare(strict_types=1);

define('BASE_DIR', dirname(__DIR__));

require_once BASE_DIR.'/vendor/autoload.php';

use Application\PhpOnIzi;

$test = (new PhpOnIzi)->test();

dd(dirname(__DIR__));
