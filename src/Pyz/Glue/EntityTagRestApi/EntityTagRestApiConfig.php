<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
