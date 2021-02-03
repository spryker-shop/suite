<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

$file = APPLICATION_ROOT_DIR . '/vendor/codeception/c3/c3.php';
if (file_exists($file)) {
    require_once $file;
    define('MY_APP_STARTED', true);
}
