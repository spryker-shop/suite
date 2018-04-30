<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Client\CustomerAccessPermission;

use Spryker\Client\CustomerAccessPermission\Plugin\SeePricePermissionPlugin;
use Spryker\Client\CustomerAccessPermission\CustomerAccessPermissionConfig as SprykerCustomerAccessPermissionConfig;

class CustomerAccessPermissionConfig extends SprykerCustomerAccessPermissionConfig
{
    protected const CONTENT_PERMISSION_PLUGIN = [
        'price' => SeePricePermissionPlugin::KEY
    ];
}
