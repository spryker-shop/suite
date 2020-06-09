<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer;
use SprykerShop\Yves\CustomerPage\Controller\OrderController as SprykerOrderController;

class OrderController extends SprykerOrderController
{
    /**
     * @param int $idSalesOrder
     *
     * @return array
     */
    protected function getOrderDetailsResponseData(int $idSalesOrder): array
    {
        $customerTransfer = $this->getLoggedInCustomerTransfer();

        $orderTransfer = new OrderTransfer();
        $orderTransfer
            ->setIdSalesOrder($idSalesOrder)
            ->setFkCustomer($customerTransfer->getIdCustomer());

        $orderTransfer = $this->getFactory()
            ->getSalesClient()
            ->getOrderDetails($orderTransfer);

        $items = $this->getFactory()
            ->getProductBundleClient()
            ->getGroupedBundleItems(
                $orderTransfer->getItems(),
                $orderTransfer->getBundleItems()
            );

        $getPaymentDetailTransfer = new PayoneGetPaymentDetailTransfer();
        $getPaymentDetailTransfer->setOrderId($idSalesOrder);
        $getPaymentDetailTransfer = $this->getFactory()
            ->getPayoneClient()->getPaymentDetail($getPaymentDetailTransfer);

        return [
            'order' => $orderTransfer,
            'items' => $items,
            'paymentDetail' => $getPaymentDetailTransfer->getPaymentDetail(),
        ];
    }
}
