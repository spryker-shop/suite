<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferValidityDataImport\Business\Model\DataSet;

interface CombinedProductOfferValidityDataSetInterface
{
    /**
     * @var string
     */
    public const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var string
     */
    public const VALID_FROM = 'product_offer_validity.valid_from';

    /**
     * @var string
     */
    public const VALID_TO = 'product_offer_validity.valid_to';
}
