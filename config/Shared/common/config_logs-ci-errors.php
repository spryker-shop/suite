<?php

use Monolog\Logger;
use Spryker\Shared\Log\LogConstants;

// ----------------------------------------------------------------------------
// ------------------------------ SERVICES ------------------------------------
// ----------------------------------------------------------------------------

// >>> LOGGING

$config[LogConstants::LOG_LEVEL] = Logger::ERROR;
$config[LogConstants::EXCEPTION_LOG_FILE_PATH_YVES]
    = $config[LogConstants::EXCEPTION_LOG_FILE_PATH_ZED]
    = $config[LogConstants::EXCEPTION_LOG_FILE_PATH_GLUE]
    = $config[LogConstants::EXCEPTION_LOG_FILE_PATH]
    = getenv('SPRYKER_LOG_STDERR') ?: 'php://stderr';
