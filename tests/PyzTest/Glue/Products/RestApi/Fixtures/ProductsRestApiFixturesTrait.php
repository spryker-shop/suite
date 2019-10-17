<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Products\RestApi\Fixtures;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use PyzTest\Glue\Products\ProductsApiTester;
use Spryker\Shared\ProductRelation\ProductRelationTypes;

trait ProductsRestApiFixturesTrait
{
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
        $I->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productConcreteTransfer->getFkProductAbstract()
        );
    }

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransferRelated
     *
     * @return void
     */
    protected function assignProductRelated(
        ProductsApiTester $I,
        ProductConcreteTransfer $productConcreteTransfer,
        ProductConcreteTransfer $productConcreteTransferRelated
    ): void {
        $I->haveProductRelation(
            $productConcreteTransferRelated->getAbstractSku(),
            $productConcreteTransfer->getFkProductAbstract(),
            ProductRelationTypes::TYPE_RELATED_PRODUCTS
        );
    }
}
