<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\ProductPrice;

use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\Transfer\DemoDataGeneratorTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Pyz\Zed\DemoDataGenerator\Business\Model\AbstractGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface;
use Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class ProductPriceGenerator extends AbstractGenerator implements ProductPriceGeneratorInterface
{
    const EMPTY_SKU = '';

    /**
     * @var array
     */
    protected $rows = [];

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $storeFacade;

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
    public function createProductPriceCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
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
        $productAbstractSkus = $this->getProductAbstractSkus();
        foreach ($productAbstractSkus as $sku) {
            $this->generateRowsForProductAbstractForPriceTypes($sku);
        }
    }

    /**
     * @return void
     */
    protected function generateRowsForProductConcrete(): void
    {
        $productConcreteSkus = $this->getProductConcreteSkus();
        foreach ($productConcreteSkus as $sku) {
            $this->generateRowsForProductConcreteForPriceTypes($sku);
        }
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function generateRowsForProductAbstractForPriceTypes(string $sku): void
    {
        $priceProductTransfer = $this->generatePriceProduct();
        $defaultPriceTypes = $this->getConfig()->getProductPriceDefaultTypes();

        foreach ($defaultPriceTypes as $priceType) {
            $priceProductTransfer
                ->setSkuProductAbstract($sku)
                ->setSkuProduct(static::EMPTY_SKU)
                ->setPriceTypeName($priceType);
            $row = $this->createPriceRow($priceProductTransfer);
            $this->rows[] = $row;
        }
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function generateRowsForProductConcreteForPriceTypes(string $sku): void
    {
        $priceProductTransfer = $this->generatePriceProduct();
        $defaultPriceTypes = $this->getConfig()->getProductPriceDefaultTypes();

        foreach ($defaultPriceTypes as $priceType) {
            $priceProductTransfer
                ->setSkuProductAbstract(static::EMPTY_SKU)
                ->setSkuProduct($sku)
                ->setPriceTypeName($priceType);
            $row = $this->createPriceRow($priceProductTransfer);
            $this->rows[] = $row;
        }
    }

    /**
     * @return array
     */
    protected function getProductAbstractSkus(): array
    {
        return $this->fileManager->readColumn($this->getConfig()->getProductAbstractCsvPath());
    }

    /**
     * @return array
     */
    protected function getProductConcreteSkus(): array
    {
        return $this->fileManager->readColumn($this->getConfig()->getProductConcreteCsvPath());
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
        $file = $filePath ? $filePath : $this->getConfig()->getProductPriceCsvPath();
        $this->fileManager->write($file, $header, $rows);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generatePriceProduct()
    {
        return (new PriceProductBuilder())
            ->withMoneyValue()
            ->withPriceType()
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return array
     */
    protected function createPriceRow(PriceProductTransfer $priceProductTransfer): array
    {
        $row = [
            'abstract_sku' => $priceProductTransfer->getSkuProductAbstract(),
            'concrete_sku' => $priceProductTransfer->getSkuProduct(),
            'price_type' => $priceProductTransfer->getPriceTypeName(),
            'value_net' => $priceProductTransfer->getMoneyValue()->getNetAmount(),
            'value_gross' => $priceProductTransfer->getMoneyValue()->getGrossAmount(),
        ];

        $row = $this->getDefaultParameters($row);

        return $row;
    }

    /**
     * @param array $row
     *
     * @return array
     */
    protected function getDefaultParameters(array $row): array
    {
        $currency = $this->storeFacade->getCurrentStore()->getDefaultCurrencyIsoCode();
        $defaultCountry = $this->storeFacade->getCurrentStore()->getName();

        return array_merge($row, [
            'currency' => $currency,
            'store' => $defaultCountry,
        ]);
    }
}
