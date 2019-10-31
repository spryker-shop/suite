<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CmsSlotBlock;

use Spryker\Shared\CmsSlotBlockCategoryConnector\CmsSlotBlockCategoryConnectorConstants;
use Spryker\Zed\CmsSlotBlock\CmsSlotBlockConfig as SprykerCmsSlotBlockConfig;

class CmsSlotBlockConfig extends SprykerCmsSlotBlockConfig
{
    /**
     * @return string[][]
     */
    public function getTemplateConditionsAssignment(): array
    {
        return [
            '@CatalogPage/views/catalog/catalog.twig' => [
                CmsSlotBlockCategoryConnectorConstants::CONDITION_KEY,
            ],
        ];
    }
}
