<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CmsSlotBlock;

use Spryker\Shared\CategoryGui\CategoryGuiConstants;
use Spryker\Shared\CmsSlotBlockProductCategoryConnector\CmsSlotBlockProductCategoryConnectorConfig;
use Spryker\Zed\CmsSlotBlock\CmsSlotBlockConfig as SprykerCmsSlotBlockConfig;

class CmsSlotBlockConfig extends SprykerCmsSlotBlockConfig
{
    /**
     * @return array
     */
    public function getTemplateConditionsAssignment(): array
    {
        return [
            '@CatalogPage/views/catalog/catalog.twig' => [
                CategoryGuiConstants::CONDITION_KEY,
            ],
            '@ProductDetailPage/views/pdp/pdp.twig' => [
                CmsSlotBlockProductCategoryConnectorConfig::CONDITION_KEY,
            ],
        ];
    }
}
