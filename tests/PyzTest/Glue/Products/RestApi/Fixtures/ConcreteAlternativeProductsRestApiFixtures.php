<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Products\RestApi\Fixtures;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use PyzTest\Glue\Products\ProductsApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Products
 * @group RestApi
 * @group ConcreteAlternativeProductsRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ConcreteAlternativeProductsRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
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
        $this->createProductConcrete($I);
        $this->createProductConcreteWithProductLabelRelationship($I);
        $this->createAlternativeRelationBetweenProducts($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    protected function createProductConcrete(ProductsApiTester $I): void
    {
        $this->productConcreteTransfer = $I->haveFullProduct();
    }

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    protected function createProductConcreteWithProductLabelRelationship(ProductsApiTester $I): void
    {
        $this->productConcreteTransferWithLabel = $I->haveFullProduct();
        $this->productLabelTransfer = $I->haveProductLabel();
        $I->haveProductLabelToAbstractProductRelation(
            $this->productLabelTransfer->getIdProductLabel(),
            $this->productConcreteTransferWithLabel->getFkProductAbstract()
        );
    }

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    protected function createAlternativeRelationBetweenProducts(ProductsApiTester $I): void
    {
        $I->haveProductAlternative($this->productConcreteTransfer, $this->productConcreteTransferWithLabel->getSku());
        $I->haveProductAlternative($this->productConcreteTransferWithLabel, $this->productConcreteTransfer->getSku());
    }
}
