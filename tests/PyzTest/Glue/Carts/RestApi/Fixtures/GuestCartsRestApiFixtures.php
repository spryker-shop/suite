<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi\Fixtures;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
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

    public const QUANTITY_FOR_ITEM_UPDATE = 33;
    public const TEST_GUEST_CART_NAME = 'Test guest cart name';
    public const CURRENCY_EUR = 'EUR';
    public const ANONYMOUS_PREFIX = 'anonymous:';

    /**
     * @var string
     */
    protected $guestCustomerReference;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer1;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer2;

    /**
     * @var string
     */
    protected $valueForAnonymousCustomerReference2;

    /**
     * @var string
     */
    protected $valueForAnonymousCustomerReference3;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $guestQuoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $guestQuoteTransfer1;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $guestQuoteTransfer2;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $emptyGuestQuoteTransfer;

    /**
     * @return string
     */
    public function getGuestCustomerReference(): string
    {
        return $this->guestCustomerReference;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getGuestQuoteTransfer(): QuoteTransfer
    {
        return $this->guestQuoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer1(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer1;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer2(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer2;
    }

    /**
     * @return string
     */
    public function getValueForAnonymousCustomerReference2(): string
    {
        return $this->valueForAnonymousCustomerReference2;
    }

    /**
     * @return string
     */
    public function getValueForAnonymousCustomerReference3(): string
    {
        return $this->valueForAnonymousCustomerReference3;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getGuestQuoteTransfer2(): QuoteTransfer
    {
        return $this->guestQuoteTransfer2;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getEmptyGuestQuoteTransfer(): QuoteTransfer
    {
        return $this->emptyGuestQuoteTransfer;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CartsApiTester $I): FixturesContainerInterface
    {
        $this->productConcreteTransfer = $I->haveFullProduct();
        $this->guestCustomerReference = $this->createGuestCustomerReference();
        $guestCustomerTransfer = (new CustomerTransfer())
            ->setCustomerReference(static::ANONYMOUS_PREFIX . $this->guestCustomerReference);
        $this->guestQuoteTransfer = $this->createPersistentQuote($I, $guestCustomerTransfer, [$this->productConcreteTransfer]);

        $this->productConcreteTransfer1 = $this->createProduct($I);
        $this->productConcreteTransfer2 = $this->createProduct($I);

        $this->valueForAnonymousCustomerReference2 = $this->createValueForAnonymousCustomerReference();
        $this->valueForAnonymousCustomerReference3 = $this->createValueForAnonymousCustomerReference();

        $this->guestQuoteTransfer2 = $this->createPersistentQuote(
            $I,
            (new CustomerTransfer())->setCustomerReference(static::ANONYMOUS_PREFIX . $this->valueForAnonymousCustomerReference2),
            [$this->productConcreteTransfer1]
        );
        $this->emptyGuestQuoteTransfer = $this->createEmptyQuote(
            $I,
            static::ANONYMOUS_PREFIX . $this->valueForAnonymousCustomerReference3
        );

        return $this;
    }

    /**
     * @return string
     */
    protected function createValueForAnonymousCustomerReference(): string
    {
        return uniqid('testReference', true);
    }
}
