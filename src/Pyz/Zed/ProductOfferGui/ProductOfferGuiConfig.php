<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ProductOfferGui;

use Spryker\Zed\ProductOfferGui\ProductOfferGuiConfig as SprykerProductOfferGuiConfig;

class ProductOfferGuiConfig extends SprykerProductOfferGuiConfig
{
    /**
     * @var list<string>
     */
    protected const PRODUCT_OFFER_TABLE_FILTER_FORM_EXTERNAL_FIELD_NAMES = [
        'id-merchant',
    ];
}
