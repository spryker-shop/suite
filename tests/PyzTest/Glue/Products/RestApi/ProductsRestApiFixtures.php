<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Products\RestApi;

use Generated\Shared\DataBuilder\ProductLabelLocalizedAttributesBuilder;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use PyzTest\Glue\Products\ProductsApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group Products
 * @group RestApi
 * @group ProductAbstractRestFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ProductsRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
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
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(ProductsApiTester $I): FixturesContainerInterface
    {
        $this->productConcreteTransfer = $this->createProductConcrete($I);
        $this->productConcreteTransferWithLabel = $this->createProductConcrete($I);
        $this->productLabelTransfer = $this->createProductLabel($I);
        $this->assignLabelToProduct($I, $this->productLabelTransfer, $this->productConcreteTransferWithLabel);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductConcrete(ProductsApiTester $I): ProductConcreteTransfer
    {
        return $I->haveFullProduct();
    }

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function createProductLabel(ProductsApiTester $I): ProductLabelTransfer
    {
        return $I->haveProductLabel([
            ProductLabelTransfer::VALID_FROM => null,
            ProductLabelTransfer::VALID_TO => null,
        ]);
    }

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function assignLabelToProduct(
        ProductsApiTester $I,
        ProductLabelTransfer $productLabelTransfer,
        ProductConcreteTransfer $productConcreteTransfer
    ): void {
        $productLabelLocalizedAttributesTransfer = (new ProductLabelLocalizedAttributesBuilder([
            ProductLabelLocalizedAttributesTransfer::FK_LOCALE => $I->getLocator()->locale()->facade()->getCurrentLocale()->getIdLocale(),
            ProductLabelLocalizedAttributesTransfer::FK_PRODUCT_LABEL => $productLabelTransfer->getIdProductLabel(),
        ]))->build();

        $productLabelTransfer->addLocalizedAttributes($productLabelLocalizedAttributesTransfer);

        $I->getLocator()->productLabel()->facade()->updateLabel($productLabelTransfer);

        $I->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productConcreteTransfer->getFkProductAbstract()
        );
    }
}
