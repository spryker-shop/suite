<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductPrice\Writer;

use Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductPrice\ProductPriceHydratorStep;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\ProductPricePropelDataSetWriter as WriterProductPricePropelDataSetWriter;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class ProductPricePropelDataSetWriter extends WriterProductPricePropelDataSetWriter
{
    protected const COLUMN_ABSTRACT_SKU = ProductPriceHydratorStep::COLUMN_ABSTRACT_SKU;
    protected const COLUMN_CONCRETE_SKU = ProductPriceHydratorStep::COLUMN_CONCRETE_SKU;
    protected const COLUMN_STORE = ProductPriceHydratorStep::COLUMN_STORE;
    protected const COLUMN_CURRENCY = ProductPriceHydratorStep::COLUMN_CURRENCY;
    protected const COLUMN_PRICE_GROSS = ProductPriceHydratorStep::COLUMN_PRICE_GROSS;
    protected const COLUMN_PRICE_NET = ProductPriceHydratorStep::COLUMN_PRICE_NET;
    protected const COLUMN_PRICE_DATA = ProductPriceHydratorStep::COLUMN_PRICE_DATA;
    protected const COLUMN_PRICE_DATA_CHECKSUM = ProductPriceHydratorStep::COLUMN_PRICE_DATA_CHECKSUM;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository $productRepository
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Currency\Business\CurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        ProductRepository $productRepository,
        StoreFacadeInterface $storeFacade,
        CurrencyFacadeInterface $currencyFacade
    ) {
        parent::__construct($productRepository, $storeFacade, $currencyFacade);
    }
}
