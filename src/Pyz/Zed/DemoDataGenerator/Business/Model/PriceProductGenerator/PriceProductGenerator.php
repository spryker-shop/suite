<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\PriceProductGenerator;

use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\Transfer\PriceProductTransfer;
use Pyz\Zed\DemoDataGenerator\Business\Model\AbstractGenerator;
use Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface;
use Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig;
use Spryker\Shared\Kernel\Store;

class PriceProductGenerator extends AbstractGenerator implements PriceProductGeneratorInterface
{
    /**
     * @var array
     */
    protected $rows = [];

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface $fileManager
     * @param \Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig $config
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        FileManagerInterface $fileManager,
        DemoDataGeneratorConfig $config,
        Store $store
    ) {
        parent::__construct($fileManager, $config);
        $this->store = $store;
    }

    /**

    /**
     *
     * @return void
     */
    public function createPriceProductCsvDemoData(): void
    {
        $this->generateRowsForProductAbstract();
        $this->generateRowsForProductConcrete();

        $header = array_keys($this->rows[0]);
        $this->writeCsv($header, $this->rows);
    }

    /**
     * @return void
     */
    protected function generateRowsForProductAbstract(): void
    {
        $productAbstractSkus = $this->getProductAbstractSkus();
        $this->generateRows($productAbstractSkus);
    }

    /**
     * @return void
     */
    protected function generateRowsForProductConcrete(): void
    {
        $productConcreteSkus = $this->getProductConcreteSkus();
        $this->generateRows($productConcreteSkus);
    }

    /**
     * @param array $skus
     *
     * @return void
     */
    protected function generateRows(array $skus): void
    {
        foreach ($skus as $sku) {
            $this->generateRowsForPriceTypes($sku);
        }
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function generateRowsForPriceTypes(string $sku): void
    {
        $priceProductTransfer = $this->generatePriceProduct();
        $defaultPriceTypes = $this->getConfig()->getProductPriceDefaultTypes();

        foreach ($defaultPriceTypes as $priceType) {
            $priceProductTransfer
                ->setSkuProductAbstract($sku)
                ->setSkuProduct('')
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
     *
     * @return void
     */
    protected function writeCsv(array $header, array $rows): void
    {
        $this->fileManager->write($this->getConfig()->getProductPriceCsvPath(), $header, $rows);
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
        $currency = $this->store->getDefaultCurrencyCode();
        $defaultCountry = $this->store->getCurrentCountry();

        return array_merge($row, [
            'currency' => $currency,
            'store' => $defaultCountry,
        ]);
    }
}
