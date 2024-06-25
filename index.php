<?php
/** retsry wearestdy*/
declare(strict_types=1);

const BASE_DIR = __DIR__;

require_once BASE_DIR.'/vendor/autoload.php';

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_NOTICE);

$container = require_once BASE_DIR.'/application/config/container.php';
$app = $container->get('app');

$app->handle();
