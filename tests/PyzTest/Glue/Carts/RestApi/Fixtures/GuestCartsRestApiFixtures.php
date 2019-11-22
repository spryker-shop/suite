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
 * @group GuestCartsRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class GuestCartsRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    use CartsRestApiFixturesTrait;

    protected const ANONYMOUS_PREFIX = 'anonymous:';

    /**
     * @var string
     */
    protected $guestCustomerReference;

    /**
     * @var string
     */
    protected $guestCustomerReferenceWithLabel;

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
    public function getGuestCustomerReference(): string
    {
        return $this->guestCustomerReference;
    }

    /**
     * @return string
     */
    public function getGuestCustomerReferenceWithLabel(): string
    {
        return $this->guestCustomerReferenceWithLabel;
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
        $this->createGuestQuote($I);
        $this->createQuoteWithProductLabelRelationship($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function createGuestQuote(CartsApiTester $I): void
    {
        $this->productConcreteTransfer = $I->haveFullProduct();
        $this->guestCustomerReference = $this->createGuestCustomerReference();
        $guestCustomerTransfer = (new CustomerTransfer())
            ->setCustomerReference(static::ANONYMOUS_PREFIX . $this->guestCustomerReference);
        $this->guestQuoteTransfer = $this->createPersistentQuote($I, $guestCustomerTransfer, [$this->productConcreteTransfer]);
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function createQuoteWithProductLabelRelationship(CartsApiTester $I): void
    {
        $this->productConcreteTransferWithLabel = $I->haveFullProduct();
        $this->productLabelTransfer = $I->haveProductLabel();

        $I->haveProductLabelToAbstractProductRelation(
            $this->productLabelTransfer->getIdProductLabel(),
            $this->productConcreteTransferWithLabel->getFkProductAbstract()
        );

        $this->guestCustomerReferenceWithLabel = $this->createGuestCustomerReference();
        $guestCustomerTransferWithLabel = (new CustomerTransfer())
            ->setCustomerReference(static::ANONYMOUS_PREFIX . $this->guestCustomerReferenceWithLabel);
        $this->guestQuoteTransferWithLabel = $this->createPersistentQuote($I, $guestCustomerTransferWithLabel, [$this->productConcreteTransferWithLabel]);
    }
}
