<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\ProductImage;

use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\DataBuilder\ProductImageSetBuilder;
use Generated\Shared\Transfer\DemoDataGeneratorTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Pyz\Zed\DemoDataGenerator\Business\Model\AbstractGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface;
use Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class ProductImageGenerator extends AbstractGenerator implements ProductImageGeneratorInterface
{
    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var array
     */
    protected $rows = [];

    /**
     * @param \Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface $fileManager
     * @param \Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig $config
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     */
    public function __construct(
        FileManagerInterface $fileManager,
        DemoDataGeneratorConfig $config,
        StoreFacadeInterface $storeFacade
    ) {
        parent::__construct($fileManager, $config);
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductImageCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $filePath = $demoDataGeneratorTransfer->getFilePath();

        $this->generateRowsForProductAbstract();
        $this->generateRowsForProductConcrete();

        $header = array_keys($this->rows[0]);
        $this->writeCsv($header, $this->rows, $filePath);
    }

    /**
     * @return void
     */
    protected function generateRowsForProductAbstract(): void
    {
        $productAbstractSkus = $this->readProductAbstractFromCsv();
        $productConcreteTransfer = $this->generateProductConcrete();

        foreach ($productAbstractSkus as $sku) {
            $productConcreteTransfer->setAbstractSku($sku);
            $productConcreteTransfer->setSku('');
            $this->generateRowsForProductImagePerStore($productConcreteTransfer);
        }
    }

    /**
     * @return void
     */
    protected function generateRowsForProductConcrete(): void
    {
        $productConcreteSkus = $this->readProductConcreteFromCsv();
        $productConcreteTransfer = $this->generateProductConcrete();

        foreach ($productConcreteSkus as $sku) {
            $productConcreteTransfer->setAbstractSku('');
            $productConcreteTransfer->setSku($sku);
            $this->generateRowsForProductImagePerStore($productConcreteTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return void
     */
    protected function generateRowsForProductImagePerStore(ProductConcreteTransfer $productTransfer): void
    {
        $productImageTransfer = $this->generateProductImage();
        $productImageSetTransfer = $this->generateProductImageSet();
        $allowedLocales = $this->storeFacade->getCurrentStore()->getAvailableLocaleIsoCodes();

        foreach ($allowedLocales as $allowedLocale) {
            $locale = new LocaleTransfer();
            $locale->setLocaleName($allowedLocale);
            $productImageSetTransfer->setLocale($locale);
            $row = $this->createProductImageRow($productImageSetTransfer, $productImageTransfer, $productTransfer);
            $this->rows[] = $row;
        }
    }

    /**
     * @return array
     */
    protected function readProductAbstractFromCsv(): array
    {
        return $this->getFileManager()->readColumn($this->getConfig()->getProductAbstractCsvPath());
    }

    /**
     * @return array
     */
    protected function readProductConcreteFromCsv(): array
    {
        return $this->getFileManager()->readColumn($this->getConfig()->getProductConcreteCsvPath());
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateProductConcrete(): ProductConcreteTransfer
    {
        return (new ProductConcreteBuilder())->build();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return array
     */
    protected function createProductImageRow(
        ProductImageSetTransfer $productImageSetTransfer,
        ProductImageTransfer $productImageTransfer,
        ProductConcreteTransfer $productTransfer
    ): array {
        $row = [
            'image_set_name' => $productImageSetTransfer->getName(),
            'external_url_large' => $productImageTransfer->getExternalUrlLarge(),
            'external_url_small' => $productImageTransfer->getExternalUrlSmall(),
            'locale' => $productImageSetTransfer->getLocale()->getLocaleName(),
            'abstract_sku' => $productTransfer->getAbstractSku(),
            'concrete_sku' => $productTransfer->getSku(),
        ];

        return $row;
    }

    /**
     * @param array $header
     * @param array $rows
     * @param string|null $filePath
     *
     * @return void
     */
    protected function writeCsv(array $header, array $rows, ?string $filePath): void
    {
        $file = $filePath ? $filePath : $this->getConfig()->getProductImageCsvPath();
        $this->fileManager->write($file, $header, $rows);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductImageTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateProductImage(): ProductImageTransfer
    {
        return (new ProductImageBuilder())->build();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateProductImageSet(): ProductImageSetTransfer
    {
        return (new ProductImageSetBuilder())->build();
    }
}
