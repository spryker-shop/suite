<?php

use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\Queue\QueueConstants;

require('config_default-docker.php');

$config[QueueConstants::QUEUE_WORKER_OUTPUT_FILE_NAME] = APPLICATION_ROOT_DIR . '/data/DE/logs/ZED/queue.out';
$config[PropelConstants::LOG_FILE_PATH] = APPLICATION_ROOT_DIR . '/data/DE/logs/ZED/propel.out';

$config[LogConstants::LOG_FILE_PATH_YVES] = APPLICATION_ROOT_DIR . '/data/DE/logs/YVES/application.out';
$config[LogConstants::LOG_FILE_PATH_ZED] = APPLICATION_ROOT_DIR . '/data/DE/logs/ZED/application.out';
$config[LogConstants::LOG_FILE_PATH_GLUE] = APPLICATION_ROOT_DIR . '/data/DE/logs/GLUE/application.out';

$config[LogConstants::EXCEPTION_LOG_FILE_PATH_YVES] = APPLICATION_ROOT_DIR . '/data/DE/logs/YVES/exception.out';
$config[LogConstants::EXCEPTION_LOG_FILE_PATH_ZED] = APPLICATION_ROOT_DIR . '/data/DE/logs/ZED/exception.out';
$config[LogConstants::EXCEPTION_LOG_FILE_PATH_GLUE] = APPLICATION_ROOT_DIR . '/data/DE/logs/GLUE/exception.out';
