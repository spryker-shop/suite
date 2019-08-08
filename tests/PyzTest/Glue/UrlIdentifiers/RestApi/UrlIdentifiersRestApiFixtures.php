<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\UrlIdentifiers\RestApi;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use PyzTest\Glue\UrlIdentifiers\UrlIdentifiersRestApiTester;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Url\Business\UrlFacade;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group UrlIdentifiers
 * @group RestApi
 * @group UrlIdentifiersRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class UrlIdentifiersRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductUrlTransfer
     */
    protected $productUrlTransfer;

    /**
     * @var \Generated\Shared\Transfer\UrlTransfer
     */
    protected $categoryUrlTransfer;

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function getProductUrlTransfer(): ProductUrlTransfer
    {
        return $this->productUrlTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getCategoryUrlTransfer(): UrlTransfer
    {
        return $this->categoryUrlTransfer;
    }

    /**
     * @param \PyzTest\Glue\UrlIdentifiers\UrlIdentifiersRestApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(UrlIdentifiersRestApiTester $I): FixturesContainerInterface
    {
        $this->createProductConcrete($I);
        $this->createProductUrl($I);
        $this->createCategoryUrl($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\UrlIdentifiers\UrlIdentifiersRestApiTester $I
     *
     * @return void
     */
    protected function createProductConcrete(UrlIdentifiersRestApiTester $I): void
    {
        $this->productConcreteTransfer = $I->haveFullProduct();
    }

    /**
     * @param \PyzTest\Glue\UrlIdentifiers\UrlIdentifiersRestApiTester $I
     *
     * @return void
     */
    protected function createProductUrl(UrlIdentifiersRestApiTester $I): void
    {
        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setIdProductAbstract($this->productConcreteTransfer->getFkProductAbstract());

        $this->productUrlTransfer = $I->getProductFacade()->getProductUrl($productAbstractTransfer);
    }

    /**
     * @param \PyzTest\Glue\UrlIdentifiers\UrlIdentifiersRestApiTester $I
     *
     * @return void
     */
    protected function createCategoryUrl(UrlIdentifiersRestApiTester $I): void
    {
        $categoryTransfer = $I->haveLocalizedCategory();
        $this->categoryUrlTransfer = (new UrlFacade())
            ->getResourceUrlByCategoryNodeIdAndLocale(
                $categoryTransfer->getCategoryNode()->getIdCategoryNode(),
                (new LocaleFacade())->getCurrentLocale()
            );
    }
}
