<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Glue\EntityTagRestApi;

use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\EntityTagRestApi\EntityTagRestApiConfig as SprykerEntityTagRestApiConfig;

class EntityTagRestApiConfig extends SprykerEntityTagRestApiConfig
{
    protected const RESOURCES_ENTITY_TAG_REQUIRED = [
        CartsRestApiConfig::RESOURCE_CARTS,
    ];
}
