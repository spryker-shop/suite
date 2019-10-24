<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi\Fixtures;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Glue\Carts\CartsApiTester;

trait CartsRestApiFixturesTrait
{
    /**
     * @return string
     */
    protected function createGuestCustomerReference(): string
    {
        return uniqid('testReference', true);
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createPersistentQuote(CartsApiTester $I, CustomerTransfer $customerTransfer, array $productConcreteTransfers): QuoteTransfer
    {
        return $I->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::TOTALS => (new TotalsTransfer())->setPriceToPay(random_int(1000, 10000)),
            QuoteTransfer::ITEMS => $this->mapProductConcreteTransfersToQuoteTransferItems($productConcreteTransfers),
            QuoteTransfer::STORE => [StoreTransfer::NAME => 'DE'],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return array
     */
    protected function mapProductConcreteTransfersToQuoteTransferItems(array $productConcreteTransfers): array
    {
        $quoteTransferItems = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $quoteTransferItems[] = [
                ItemTransfer::SKU => $productConcreteTransfer->getSku(),
                ItemTransfer::GROUP_KEY => $productConcreteTransfer->getSku(),
                ItemTransfer::ABSTRACT_SKU => $productConcreteTransfer->getAbstractSku(),
                ItemTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                ItemTransfer::UNIT_PRICE => random_int(100, 1000),
                ItemTransfer::QUANTITY => 5,
            ];
        }

        return $quoteTransferItems;
    }
}
