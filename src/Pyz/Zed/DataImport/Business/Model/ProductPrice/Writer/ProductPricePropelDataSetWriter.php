<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer;

use Generated\Shared\Transfer\SpyPriceProductEntityTransfer;
use Generated\Shared\Transfer\SpyPriceTypeEntityTransfer;
use Orm\Zed\Currency\Persistence\Base\SpyCurrency;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Orm\Zed\PriceProduct\Persistence\Base\SpyPriceProduct;
use Orm\Zed\PriceProduct\Persistence\Base\SpyPriceType;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Orm\Zed\Store\Persistence\Base\SpyStore;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\ProductPriceHydratorStep;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\Product\Dependency\ProductEvents;

class ProductPricePropelDataSetWriter extends DataImporterPublisher implements DataSetWriterInterface
{
    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository
     */
    protected $productRepository;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository $productRepository
     */
    public function __construct(
        DataImportToEventFacadeInterface $eventFacade,
        ProductRepository $productRepository
    ) {
        parent::__construct($eventFacade);
        $this->productRepository = $productRepository;
    }

    /**
     * @var \Orm\Zed\Currency\Persistence\SpyCurrency[]
     */
    protected static $currencyCache = [];

    /**
     * @var \Orm\Zed\Store\Persistence\SpyStore[]
     */
    protected static $storeCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $priceTypeEntity = $this->findOrCreatePriceType($dataSet);
        $productPriceEntity = $this->findOrCreateProductPrice($dataSet, $priceTypeEntity);
        $this->findOrCreatePriceProductStore($dataSet, $productPriceEntity);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceType
     */
    protected function findOrCreatePriceType(DataSetInterface $dataSet)
    {
        $priceTypeTransfer = $this->getPriceTypeTransfer($dataSet);

        $priceTypeEntity = SpyPriceTypeQuery::create()
            ->filterByName($priceTypeTransfer->getName())
            ->findOneOrCreate();

        if ($priceTypeEntity->isNew() || $priceTypeEntity->isModified()) {
            $priceTypeEntity->setPriceModeConfiguration(SpyPriceTypeTableMap::COL_PRICE_MODE_CONFIGURATION_BOTH);
            $priceTypeEntity->save();
        }

        return $priceTypeEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceType $priceTypeEntity
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct
     */
    protected function findOrCreateProductPrice(DataSetInterface $dataSet, SpyPriceType $priceTypeEntity): SpyPriceProduct
    {
        $query = SpyPriceProductQuery::create();
        $query->filterByFkPriceType($priceTypeEntity->getIdPriceType());

        if (empty($dataSet[ProductPriceHydratorStep::KEY_ABSTRACT_SKU]) && empty($dataSet[ProductPriceHydratorStep::KEY_CONCRETE_SKU])) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                'One of "%s" or "%s" must be in the data set. Given: "%s"',
                ProductPriceHydratorStep::KEY_ABSTRACT_SKU,
                ProductPriceHydratorStep::KEY_CONCRETE_SKU,
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        if (!empty($dataSet[ProductPriceHydratorStep::KEY_ABSTRACT_SKU])) {
            $idProductAbstract = $this->productRepository->getIdProductAbstractByAbstractSku($dataSet[ProductPriceHydratorStep::KEY_ABSTRACT_SKU]);
            $query->filterByFkProductAbstract($idProductAbstract);
            $this->addEvent(PriceProductEvents::PRICE_ABSTRACT_PUBLISH, $idProductAbstract);
            $this->addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $idProductAbstract);
        } else {
            $idProduct = $this->productRepository->getIdProductByConcreteSku($dataSet[ProductPriceHydratorStep::KEY_CONCRETE_SKU]);
            $this->addEvent(PriceProductEvents::PRICE_CONCRETE_PUBLISH, $idProduct);
            $query->filterByFkProduct($idProduct);
        }

        $productPriceEntity = $query->findOneOrCreate();
        $productPriceEntity->save();

        return $productPriceEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet $dataSet
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct $spyPriceProduct
     *
     *
     * return void
     *
     * @return void
     */
    protected function findOrCreatePriceProductStore(DataSetInterface $dataSet, SpyPriceProduct $spyPriceProduct): void
    {
        $storeEntity = $this->getStore($dataSet[ProductPriceHydratorStep::KEY_STORE]);
        $currencyEntity = $this->getCurrency($dataSet[ProductPriceHydratorStep::KEY_CURRENCY]);

        $priceProductStoreEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($storeEntity->getPrimaryKey())
            ->filterByFkCurrency($currencyEntity->getPrimaryKey())
            ->filterByFkPriceProduct($spyPriceProduct->getPrimaryKey())
            ->findOneOrCreate();

        $priceProductStoreEntity->setGrossPrice($dataSet[ProductPriceHydratorStep::KEY_PRICE_GROSS]);
        $priceProductStoreEntity->setNetPrice($dataSet[ProductPriceHydratorStep::KEY_PRICE_NET]);

        $priceProductStoreEntity->save();
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->triggerEvents();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\SpyPriceTypeEntityTransfer
     */
    protected function getPriceTypeTransfer(DataSetInterface $dataSet): SpyPriceTypeEntityTransfer
    {
        return $dataSet[ProductPriceHydratorStep::PRICE_TYPE_TRANSFER];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductEntityTransfer
     */
    protected function getPriceProductTransfer(DataSetInterface $dataSet): SpyPriceProductEntityTransfer
    {
        return $dataSet[ProductPriceHydratorStep::PRICE_PRODUCT_TRANSFER];
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return \Orm\Zed\Currency\Persistence\SpyCurrency
     */
    protected function getCurrency(string $currencyIsoCode): SpyCurrency
    {
        if (isset(static::$currencyCache[$currencyIsoCode])) {
            return static::$currencyCache[$currencyIsoCode];
        }

        $currencyEntity = SpyCurrencyQuery::create()
            ->filterByCode($currencyIsoCode)
            ->findOne();

        static::$currencyCache[$currencyIsoCode] = $currencyEntity;

        return $currencyEntity;
    }

    /**
     * @param string $storeName
     *
     * @return \Orm\Zed\Store\Persistence\SpyStore
     */
    protected function getStore(string $storeName): SpyStore
    {
        if (isset(static::$storeCache[$storeName])) {
            return static::$storeCache[$storeName];
        }

        $storeEntity = SpyStoreQuery::create()
            ->filterByName($storeName)
            ->findOne();

        static::$storeCache[$storeName] = $storeEntity;

        return $storeEntity;
    }
}
