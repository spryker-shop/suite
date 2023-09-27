<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi\Fixtures;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Checkout
 * @group RestApi
 * @group GuestCheckoutDataRelationshipsFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class GuestCheckoutDataRelationshipsFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var string
     */
    protected const ANONYMOUS_PREFIX = 'anonymous:';

    /**
     * @var string
     */
    protected string $guestCustomerReference;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected QuoteTransfer $guestQuoteTransfer;

    /**
     * @return string
     */
    public function getGuestCustomerReference(): string
    {
        return $this->guestCustomerReference;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getGuestQuoteTransfer(): QuoteTransfer
    {
        return $this->guestQuoteTransfer;
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CheckoutApiTester $I): FixturesContainerInterface
    {
        $I->truncateSalesOrderThresholds();
        $this->guestCustomerReference = $I->createGuestCustomerReference();

        $guestCustomerTransfer = $I->createCustomerTransfer([
            CustomerTransfer::CUSTOMER_REFERENCE => static::ANONYMOUS_PREFIX . $this->guestCustomerReference,
        ]);
        $this->guestQuoteTransfer = $I->havePersistentQuoteWithItemAndItemServicePoint($guestCustomerTransfer);

        return $this;
    }
}
