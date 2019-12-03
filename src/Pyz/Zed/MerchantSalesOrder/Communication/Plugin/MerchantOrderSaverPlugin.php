<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantSalesOrder\Communication\Plugin;

use Generated\Shared\Transfer\MerchantSalesOrderTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface getFacade()
 */
class MerchantOrderSaverPlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - For every item with merchant offer save related data to `spy_merchant_sales_order`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productOfferTransfer = $itemTransfer->getProductOffer();

            if (!$productOfferTransfer) {
                continue;
            }

            $this->getFacade()->createMerchantSalesOrder(
                $this->createMerchantSalesOrderTransfer($productOfferTransfer, $saveOrderTransfer)
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSalesOrderTransfer
     */
    protected function createMerchantSalesOrderTransfer(
        ProductOfferTransfer $productOfferTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): MerchantSalesOrderTransfer {
        $merchantSalesOrderTransfer = new MerchantSalesOrderTransfer();
        $merchantSalesOrderTransfer->setMerchantReference($productOfferTransfer->getMerchantReference());
        $merchantSalesOrderTransfer->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $merchantSalesOrderTransfer->setOrderReference($saveOrderTransfer->getOrderReference());

        return $merchantSalesOrderTransfer;
    }
}
