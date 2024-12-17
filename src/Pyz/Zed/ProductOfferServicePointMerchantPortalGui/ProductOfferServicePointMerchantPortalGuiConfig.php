<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ProductOfferServicePointMerchantPortalGui;

use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\ProductOfferServicePointMerchantPortalGuiConfig as SprykerProductOfferServicePointMerchantPortalGuiConfig;

class ProductOfferServicePointMerchantPortalGuiConfig extends SprykerProductOfferServicePointMerchantPortalGuiConfig
{
    /**
     * @return int|null
     */
    public function getServicePointChoicesLimit(): ?int
    {
        return 100;
    }
}
