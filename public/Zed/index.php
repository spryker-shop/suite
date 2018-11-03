<?php

use Pyz\Zed\Application\Communication\ZedBootstrap;
use Spryker\Shared\Config\Application\Environment;
use Spryker\Shared\ErrorHandler\ErrorHandlerEnvironment;

$app_dir = realpath(__DIR__ . '/../..');
define('APPLICATION_ROOT_DIR', getenv('APPLICATION_ROOT_DIR', $app_dir));

require __DIR__ . '/maintenance/maintenance.php';

define('APPLICATION', 'ZED');
defined('APPLICATION_ROOT_DIR') || define('APPLICATION_ROOT_DIR', realpath(__DIR__ . '/../..'));

require_once APPLICATION_ROOT_DIR . '/vendor/autoload.php';

Environment::initialize();

$errorHandlerEnvironment = new ErrorHandlerEnvironment();
$errorHandlerEnvironment->initialize();

$bootstrap = new ZedBootstrap();
$bootstrap
    ->boot()
    ->run();
