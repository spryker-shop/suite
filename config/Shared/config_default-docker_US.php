<?php

use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\Queue\QueueConstants;

require('config_default-docker.php');

$config[QueueConstants::QUEUE_WORKER_OUTPUT_FILE_NAME] = 'data/US/logs/ZED/queue.out';
$config[PropelConstants::LOG_FILE_PATH] = 'data/US/logs/ZED/propel.out';

$config[LogConstants::LOG_FILE_PATH_YVES] = 'data/US/logs/YVES/application.out';
$config[LogConstants::LOG_FILE_PATH_ZED] = 'data/US/logs/ZED/application.out';
$config[LogConstants::LOG_FILE_PATH_GLUE] = 'data/US/logs/GLUE/application.out';

$config[LogConstants::EXCEPTION_LOG_FILE_PATH_YVES] = 'data/US/logs/YVES/exception.out';
$config[LogConstants::EXCEPTION_LOG_FILE_PATH_ZED] = 'data/US/logs/ZED/exception.out';
$config[LogConstants::EXCEPTION_LOG_FILE_PATH_GLUE] = 'data/US/logs/GLUE/exception.out';
