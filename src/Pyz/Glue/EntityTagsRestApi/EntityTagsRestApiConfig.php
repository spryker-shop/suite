<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\EntityTagsRestApi;

use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\EntityTagsRestApi\EntityTagsRestApiConfig as SprykerEntityTagsRestApiConfig;

class EntityTagsRestApiConfig extends SprykerEntityTagsRestApiConfig
{
    protected const RESOURCES_ENTITY_TAG_REQUIRED = [
        CartsRestApiConfig::RESOURCE_CARTS,
    ];
}
