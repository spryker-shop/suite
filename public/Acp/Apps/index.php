<?php

/**
 * Used when in suite-nonsplit context and render content from an App when the App is using Zed as "Page renderer"
 */
if (file_exists(__DIR__ . '/../../../app-store-suite/public/Zed/index.php')) {
    require __DIR__ . '/../../../app-store-suite/public/Zed/index.php';

    return;
}

/**
 * Used when in suite-nonsplit context and render content from an App when the App is using Backoffice as "Page renderer"
 */
require __DIR__ . '/../../../app-store-suite/public/Backoffice/index.php';
