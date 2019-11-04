<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesMerchantConnector\Communication\Plugin;

use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantSaveTransfer;
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
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $offerTransfer = $itemTransfer->getOffer();
            if (!$offerTransfer || !$offerTransfer->getProductOfferReference()) {
                //TODO: Should be removed when MP-1301 is done.
                //continue;
                $offerTransfer = new OfferTransfer();
                $offerTransfer->setProductOfferReference('offer1');
                $itemTransfer->setOffer($offerTransfer);
            }

            $this->getFacade()->createSalesOrderMerchant(
                $this->createSalesOrderMerchantSaveTransfer($offerTransfer, $saveOrderTransfer)
            );
        }
    }

    /**4
     * @param OfferTransfer $offerTransfer
     * @param SaveOrderTransfer $saveOrderTransfer
     *
     * @return SalesOrderMerchantSaveTransfer
     */
    protected function createSalesOrderMerchantSaveTransfer(
        OfferTransfer $offerTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SalesOrderMerchantSaveTransfer {
        $salesOrderMerchantSaveTransfer = new SalesOrderMerchantSaveTransfer();
        $salesOrderMerchantSaveTransfer->setOfferReference($offerTransfer->getProductOfferReference());
        $salesOrderMerchantSaveTransfer->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        return  $salesOrderMerchantSaveTransfer;
    }
}
