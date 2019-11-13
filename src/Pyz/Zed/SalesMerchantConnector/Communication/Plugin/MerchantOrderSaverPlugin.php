<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesMerchantConnector\Communication\Plugin;

use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesMerchantConnector\Business\SalesMerchantConnectorFacadeInterface getFacade()
 */
class MerchantOrderSaverPlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - For every item with merchant offer save related data to `spy_sales_order_merchant`.
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
            $offerTransfer = $itemTransfer->getOffer();
            if (!$offerTransfer) {
                continue;
            }

            $this->getFacade()->createSalesOrderMerchant(
                $this->createSalesOrderMerchantTransfer($offerTransfer, $saveOrderTransfer)
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer
     */
    protected function createSalesOrderMerchantTransfer(
        OfferTransfer $offerTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SalesOrderMerchantTransfer {
        $salesOrderMerchantSaveTransfer = new SalesOrderMerchantTransfer();
        $salesOrderMerchantSaveTransfer->setMerchantReference($offerTransfer->getMerchantReference());
        $salesOrderMerchantSaveTransfer->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $salesOrderMerchantSaveTransfer->setOrderReference($saveOrderTransfer->getOrderReference());

        return $salesOrderMerchantSaveTransfer;
    }
}
