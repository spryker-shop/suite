<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace VolumeDataGenerationTest\Zed\Ssp;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantProfileBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductClass;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnit;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Ramsey\Uuid\Uuid;

/**
 * Generates orders with service product for customer with email {self::CUSTOMER_EMAIL}.
 *
 * @group VolumeDataGenerationTest
 * @group Ssp
 * @group SspVolumeServiceTest
 * @group ServiceSspAssetOrder
 */
class SspVolumeOrderTest extends Unit
{
    /**
     * @var int
     */
    /**
     * Amount of orders with service product which will be generated by the test run.
     *
     * @var int
     */
    protected const SERVICE_ORDER_COUNT = 20;

    /**
     * @var string
     */
    protected const CUSTOMER_EMAIL = 'ssp-service@volume.data';

    /**
     * @var \VolumeDataGenerationTest\Zed\Ssp\SspTester
     */
    protected SspTester $tester;

    /**
     * @return void
     */
    public function testGenerateOrder(): void
    {
        $serviceProductClassEntity = SpyProductClassQuery::create()->findOneByName('Service');

        if (!$serviceProductClassEntity) {
            $this->tester->haveProductClass([
                'name' => 'Service',
            ]);

            $serviceProductClassEntity = SpyProductClassQuery::create()->findOneByName('Service');
        }

        $taxSetEntity = SpyTaxSetQuery::create()->findOne();

        $storeTransfer = (new StoreTransfer())->setName('DE')->setIdStore(
            SpyStoreQuery::create()->findOneByName('DE')->getIdStore(),
        );

        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_PROFILE => (new MerchantProfileBuilder([]))->build(),
            MerchantTransfer::IS_ACTIVE => true,
            MerchantTransfer::STATUS => 'approved',
            MerchantTransfer::STORE_RELATION => [
                StoreRelationTransfer::STORES => [
                    $storeTransfer,
                ]],
        ]);

        $customerTransfer = $this->tester->haveCustomer(['email' => self::CUSTOMER_EMAIL]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([
            'key' => 'spryker_dummy_shipment-standard',
        ]);

        $sspAssetTransfer = $this->generateSspAsset();

        $currencyTransfer = (new CurrencyTransfer())->fromArray(
            SpyCurrencyQuery::create()->findOneByCode('EUR')->toArray(),
        );

        $categoryEntity = SpyCategoryQuery::create()->findOneByName('service');

        $this->tester->configureTestStateMachine(['DummyPayment01']);

        for ($i = 0; $i < self::SERVICE_ORDER_COUNT; $i++) {
            $product = $this->tester->generateService(
                $storeTransfer,
                $taxSetEntity,
                $serviceProductClassEntity,
                $merchantTransfer,
                $currencyTransfer,
                $categoryEntity,
            );
            $saveOrderTransfer = $this->tester->haveFullOrder([
                'item' => ['sku' => $product->getSku()],
                    'customerReference' => $customerTransfer->getCustomerReference(),
                    'shipment' => (new ShipmentTransfer())
                        ->setShipmentTypeUuid($this->tester->haveShipmentType(['key' => 'delivery'])->getUuid())
                        ->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethod())
                        ->setMethod($shipmentMethodTransfer)
                        ->setShippingAddress($this->tester->haveCustomerAddress([
                            'fkCustomer' => $customerTransfer->getIdCustomer(),
                        ])),
                    'shipmentMethod' => $shipmentMethodTransfer,
                ], 'DummyPayment01');

            $saleOrderItemId = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItem();

            (new SpySalesOrderItemSspAsset())
                ->fromArray($sspAssetTransfer->toArray())
                ->setFkSalesOrderItem($saleOrderItemId)
                ->save();

            $salesProductAbstractTypeEntity = (new SpySalesProductAbstractTypeQuery())->filterByPrimaryKey('service')
                ->findOneOrCreate();

            if ($salesProductAbstractTypeEntity->isNew()) {
                $salesProductAbstractTypeEntity->save();
            }

            (new SpySalesOrderItemProductClass())
                ->setFkSalesProductClass($serviceProductClassEntity->getIdProductClass())
                ->setFkSalesOrderItem($saleOrderItemId)
                ->save();
        }

        $this->assertTrue(false, SspTester::ALL_ENTITIES_GENERATED_MESSAGE);
    }

    /**
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    protected function generateSspAsset(): SspAssetTransfer
    {
        $companyTransfer = $this->tester->haveCompany([]);

        $businessUnitId = SpyCompanyBusinessUnitQuery::create()
            ->findOneByFkCompany($companyTransfer->getIdCompany())
            ->getIdCompanyBusinessUnit();

        $sspAssetEntity = (new SpySspAsset())
            ->setReference(Uuid::uuid4())
            ->setName('Test name')
            ->setSerialNumber(Uuid::uuid1()->toString())
            ->setStatus('pending')
            ->setFkCompanyBusinessUnit($businessUnitId);

        $sspAssetEntity->save();

        (new SpySspAssetToCompanyBusinessUnit())
            ->setFkSspAsset($sspAssetEntity->getIdSspAsset())
            ->setFkCompanyBusinessUnit($businessUnitId)
            ->save();

        return (new SspAssetTransfer())
            ->fromArray($sspAssetEntity->toArray(), true);
    }
}
