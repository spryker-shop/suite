<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductConcrete;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Pyz\Zed\DataImport\Business\Exception\InvalidDataException;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteAttributesUniqueCheckStep;
use PyzTest\Zed\DataImport\DataImportBusinessTester;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceBridge;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductConcrete
 * @group ProductConcreteAttributesUniqueCheckStepTest
 * Add your own group annotations below this line
 */
class ProductConcreteAttributesUniqueCheckStepTest extends Unit
{
    /**
     * @uses \Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteAttributesUniqueCheckStep::KEY_CONCRETE_SKU
     *
     * @var string
     */
    protected const KEY_CONCRETE_SKU = 'concrete_sku';

    /**
     * @uses \Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteAttributesUniqueCheckStep::KEY_ABSTRACT_SKU
     *
     * @var string
     */
    protected const KEY_ABSTRACT_SKU = 'abstract_sku';

    /**
     * @uses \Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteAttributesUniqueCheckStep::KEY_ATTRIBUTES
     *
     * @var string
     */
    protected const KEY_ATTRIBUTES = 'attributes';

    /**
     * @var array<string, string>
     */
    protected const ATTRIBUTES = ['key' => 'attribute'];

    /**
     * @var string
     */
    protected const CONCRETE_SKU = 'CONCRETE_SKU';

    /**
     * @var \PyzTest\Zed\DataImport\DataImportBusinessTester
     */
    protected DataImportBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldThrowAnExceptionWhenProductConcreteAttributesAreNotUnique(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([
            ProductConcreteTransfer::ATTRIBUTES => static::ATTRIBUTES,
        ]);

        $dataSet = new DataSet([
            static::KEY_CONCRETE_SKU => static::CONCRETE_SKU,
            static::KEY_ABSTRACT_SKU => $productConcreteTransfer->getAbstractSku(),
            static::KEY_ATTRIBUTES => $productConcreteTransfer->getAttributes(),
        ]);

        // Assert
        $this->expectException(InvalidDataException::class);

        // Act
        $this->createProductConcreteAttributesUniqueCheckStep()->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testShouldNotThrowAnExceptionWhenProductConcreteAttributesAreUnique(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();

        $dataSet = new DataSet([
            static::KEY_CONCRETE_SKU => static::CONCRETE_SKU,
            static::KEY_ABSTRACT_SKU => $productConcreteTransfer->getAbstractSku(),
            static::KEY_ATTRIBUTES => static::ATTRIBUTES,
        ]);

        // Act
        $this->createProductConcreteAttributesUniqueCheckStep()->execute($dataSet);
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteAttributesUniqueCheckStep
     */
    protected function createProductConcreteAttributesUniqueCheckStep(): ProductConcreteAttributesUniqueCheckStep
    {
        /** @var \Pyz\Zed\DataImport\DataImportConfig $dataImportConfig */
        $dataImportConfig = $this->tester->getModuleConfig();

        return new ProductConcreteAttributesUniqueCheckStep(
            $this->createProductRepository(),
            $this->createUtilEncodingService(),
            $dataImportConfig,
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface
     */
    protected function createProductRepository(): ProductRepositoryInterface
    {
        return new ProductRepository();
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    protected function createUtilEncodingService(): DataImportToUtilEncodingServiceInterface
    {
        return new DataImportToUtilEncodingServiceBridge(
            new UtilEncodingService(),
        );
    }
}
