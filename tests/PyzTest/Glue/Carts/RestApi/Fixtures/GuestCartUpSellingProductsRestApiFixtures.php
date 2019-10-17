<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi\Fixtures;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PyzTest\Glue\Carts\CartsApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group GuestCartsUpSellingProductsRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class GuestCartUpSellingProductsRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    use CartsRestApiFixturesTrait;

    /**
     * @var string
     */
    protected $valueForGuestCustomerReference;

    /**
     * @var string
     */
    protected $valueForGuestCustomerReferenceWithLabel;

    /**
     * @var string
     */
    protected $valueForGuestCustomerReferenceWithLabelWithEmptyCart;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransferWithLabel;

    /**
     * @var \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected $productLabelTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $guestQuoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $guestQuoteTransferWithLabel;

    /**
     * @return string
     */
    public function getValueForGuestCustomerReference(): string
    {
        return $this->valueForGuestCustomerReference;
    }

    /**
     * @return string
     */
    public function getValueForGuestCustomerReferenceWithLabel(): string
    {
        return $this->valueForGuestCustomerReferenceWithLabel;
    }

    /**
     * @return string
     */
    public function getValueForGuestCustomerReferenceWithLabelWithEmptyCart(): string
    {
        return $this->valueForGuestCustomerReferenceWithLabelWithEmptyCart;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransferWithLabel(): ProductConcreteTransfer
    {
        return $this->productConcreteTransferWithLabel;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function getProductLabelTransfer(): ProductLabelTransfer
    {
        return $this->productLabelTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getGuestQuoteTransfer(): QuoteTransfer
    {
        return $this->guestQuoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getGuestQuoteTransferWithLabel(): QuoteTransfer
    {
        return $this->guestQuoteTransferWithLabel;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CartsApiTester $I): FixturesContainerInterface
    {
        $this->productConcreteTransfer = $I->haveFullProduct();
        $this->productConcreteTransferWithLabel = $I->haveFullProduct();

        $this->productLabelTransfer = $I->haveProductLabel();
        $this->assignLabelToProduct($I, $this->productLabelTransfer, $this->productConcreteTransferWithLabel);

        $this->assignProductUpSelling($I, $this->productConcreteTransfer, $this->productConcreteTransferWithLabel);
        $this->assignProductUpSelling($I, $this->productConcreteTransferWithLabel, $this->productConcreteTransfer);

        $this->valueForGuestCustomerReference = $this->createValueForGuestCustomerReference();
        $this->valueForGuestCustomerReferenceWithLabel = $this->createValueForGuestCustomerReference();
        $this->valueForGuestCustomerReferenceWithLabelWithEmptyCart = $this->createValueForGuestCustomerReference();

        $guestCustomerTransfer = (new CustomerTransfer())
            ->setCustomerReference($I::ANONYMOUS_PREFIX . $this->valueForGuestCustomerReference);
        $guestCustomerTransferWithLabel = (new CustomerTransfer())
            ->setCustomerReference($I::ANONYMOUS_PREFIX . $this->valueForGuestCustomerReferenceWithLabel);

        $this->guestQuoteTransfer = $this->createQuote($I, $guestCustomerTransfer, [$this->productConcreteTransferWithLabel]);
        $this->guestQuoteTransferWithLabel = $this->createQuote($I, $guestCustomerTransferWithLabel, [$this->productConcreteTransfer]);

        return $this;
    }
}
