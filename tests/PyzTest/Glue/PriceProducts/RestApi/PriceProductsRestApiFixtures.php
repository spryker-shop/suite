<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\PriceProducts\RestApi;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use PyzTest\Glue\PriceProducts\PriceProductsApiTester;
use Spryker\Shared\CustomerAccess\CustomerAccessConfig;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group PriceProducts
 * @group RestApi
 * @group PriceProductsRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class PriceProductsRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected $priceProductTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerAccessTransfer|null
     */
    protected $customerAccessTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerAccessTransfer|null
     */
    protected $restrictedCustomerAccessTransfer;

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function getPriceProductTransfer(): PriceProductTransfer
    {
        return $this->priceProductTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getCustomerAccessTransfer(): CustomerAccessTransfer
    {
        return $this->customerAccessTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getRestrictedCustomerAccessTransfer(): CustomerAccessTransfer
    {
        return $this->restrictedCustomerAccessTransfer;
    }

    /**
     * @param \PyzTest\Glue\PriceProducts\PriceProductsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(PriceProductsApiTester $I): FixturesContainerInterface
    {
        $this->createProductConcrete($I);
        $this->createPriceProduct($I);
        $this->createCustomerAccess($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\PriceProducts\PriceProductsApiTester $I
     *
     * @return void
     */
    protected function createProductConcrete(PriceProductsApiTester $I): void
    {
        $this->productConcreteTransfer = $I->haveFullProduct();
    }

    /**
     * @param \PyzTest\Glue\PriceProducts\PriceProductsApiTester $I
     *
     * @return void
     */
    protected function createPriceProduct(PriceProductsApiTester $I): void
    {
        $priceTypeTransfer = $I->havePriceType();
        $currencyId = $I->haveCurrency();
        $currencyTransfer = $I->getLocator()->currency()->facade()->getByIdCurrency($currencyId);

        $this->priceProductTransfer = $I->havePriceProduct([
            PriceProductTransfer::ID_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::SKU_PRODUCT => $this->productConcreteTransfer->getSku(),
            PriceProductTransfer::ID_PRICE_PRODUCT => $this->productConcreteTransfer->getFkProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $this->productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);
    }

    /**
     * @param \PyzTest\Glue\PriceProducts\PriceProductsApiTester $I
     *
     * @return void
     */
    protected function createCustomerAccess(PriceProductsApiTester $I): void
    {
        $allCustomerAccessTransfer = $I->getLocator()->customerAccess()->facade()->getAllContentTypes();

        foreach ($allCustomerAccessTransfer->getContentTypeAccess() as $contentTypeAccessTransfer) {
            if ($contentTypeAccessTransfer->getContentType() === CustomerAccessConfig::CONTENT_TYPE_PRICE) {
                $contentTypeAccessTransfer->setIsRestricted(false);
                $this->customerAccessTransfer = (new CustomerAccessTransfer())->addContentTypeAccess($contentTypeAccessTransfer);
                $contentTypeAccessTransfer->setIsRestricted(true);
                $this->restrictedCustomerAccessTransfer = (new CustomerAccessTransfer())->addContentTypeAccess($contentTypeAccessTransfer);

                break;
            }
        }

        if (!$this->customerAccessTransfer) {
            $this->customerAccessTransfer = $I->haveCustomerAccess([
                CustomerAccessTransfer::CONTENT_TYPE_ACCESS => [
                    ContentTypeAccessTransfer::ID_UNAUTHENTICATED_CUSTOMER_ACCESS => rand(),
                    ContentTypeAccessTransfer::CONTENT_TYPE => CustomerAccessConfig::CONTENT_TYPE_PRICE,
                    ContentTypeAccessTransfer::IS_RESTRICTED => false,
                ],
            ]);
        }

        if (!$this->restrictedCustomerAccessTransfer) {
            $this->customerAccessTransfer = $I->haveCustomerAccess([
                CustomerAccessTransfer::CONTENT_TYPE_ACCESS => [
                    ContentTypeAccessTransfer::ID_UNAUTHENTICATED_CUSTOMER_ACCESS => rand(),
                    ContentTypeAccessTransfer::CONTENT_TYPE => CustomerAccessConfig::CONTENT_TYPE_PRICE,
                    ContentTypeAccessTransfer::IS_RESTRICTED => true,
                ],
            ]);
        }
    }
}
