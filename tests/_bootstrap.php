<?php

if (getenv('SPRYKER_TESTING_ENABLED')) {
    define('APPLICATION_ENV', getenv('APPLICATION_ENV'));
}

if (!getenv('SPRYKER_TEST_WEB_DRIVER_HOST')) {
    define('SPRYKER_TEST_WEB_DRIVER_HOST', 'webdriver');
}

if (!getenv('SPRYKER_TEST_IN_BROWSER')) {
    define('SPRYKER_TEST_IN_BROWSER', 'phantomjs');
}

define('APPLICATION_ROOT_DIR', dirname(__DIR__));

putenv('APPLICATION_STORE=DE');
