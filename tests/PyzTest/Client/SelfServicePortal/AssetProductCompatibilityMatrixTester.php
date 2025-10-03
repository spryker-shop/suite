<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Client\SelfServicePortal;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer;
use Generated\Shared\Transfer\SspAssetStorageTransfer;
use Generated\Shared\Transfer\SspModelStorageTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use PHPUnit\Framework\MockObject\MockObject;

class AssetProductCompatibilityMatrixTester
{
    public const ASSET_A1_REFERENCE = 'A1';

    public const ASSET_A2_REFERENCE = 'A2';

    public const ASSET_A3_REFERENCE = 'A3';

    public const PRODUCT_P1_SKU = 'P1';

    public const PRODUCT_P1_ID = 100;

    public const MODEL_M1_ID = 1;

    public const MODEL_M2_ID = 2;

    public const PRODUCT_LIST_ID = 10;

    public const LOCALE = 'en_US';

    public function setupStorageKeyBuilderMock(MockObject $synchronizationService, MockObject $storageKeyBuilderMock): MockObject
    {
        $storageKeyBuilderMock
            ->method('generateKey')
            ->willReturnCallback(function ($syncData) {
                if (method_exists($syncData, 'getReference')) {
                    return 'ssp_asset:' . $syncData->getReference();
                }
                if (method_exists($syncData, 'getIdModel')) {
                    return 'ssp_model:' . $syncData->getIdModel();
                }

                return 'unknown:key';
            });

        $synchronizationService
            ->method('getStorageKeyBuilder')
            ->willReturn($storageKeyBuilderMock);

        return $storageKeyBuilderMock;
    }

    public function setupUtilEncodingMock(MockObject $utilEncodingService): void
    {
        $utilEncodingService
            ->method('decodeJson')
            ->willReturnCallback(function ($json, $assoc) {
                return json_decode($json, $assoc);
            });
    }

    public function setupAssetPermissionCheckerMock(MockObject $permissionChecker, bool $allowAll = true): void
    {
        if ($allowAll) {
            $permissionChecker
                ->method('canViewSspAsset')
                ->willReturn(true);

            return;
        }

        $permissionChecker
            ->method('canViewSspAsset')
            ->willReturnCallback(function ($decodedStorageData) {
                return $decodedStorageData['reference'] === static::ASSET_A1_REFERENCE;
            });
    }

    public function setupAssetStorageMapperMock(MockObject $assetMapper): void
    {
        $assetMapper
            ->method('mapStorageDataToSspAssetStorageTransfer')
            ->willReturnCallback(function ($data) {
                $assetStorage = new SspAssetStorageTransfer();
                $assetStorage->setReference($data['reference']);

                if (!isset($data['ssp_models']) || !is_array($data['ssp_models'])) {
                    $assetStorage->setSspModels(new ArrayObject());

                    return $assetStorage;
                }

                $models = new ArrayObject();
                foreach ($data['ssp_models'] as $modelData) {
                    $model = new SspModelTransfer();
                    $model->setIdSspModel($modelData['id_ssp_model']);
                    $models->append($model);
                }
                $assetStorage->setSspModels($models);

                return $assetStorage;
            });
    }

    public function setupModelStorageMapperMock(MockObject $modelMapper): void
    {
        $modelMapper
            ->method('mapStorageDataToSspModelStorageTransfer')
            ->willReturnCallback(function ($data) {
                $modelStorage = new SspModelStorageTransfer();
                $modelStorage->setIdModel($data['id_model']);
                $modelStorage->setWhitelistIds($data['whitelist_ids'] ?? []);

                return $modelStorage;
            });
    }

    public function createStorageData(): array
    {
        return [
            'assetA1WithModel' => json_encode([
                'reference' => static::ASSET_A1_REFERENCE,
                'ssp_models' => [['id_ssp_model' => static::MODEL_M1_ID]],
            ]),
            'assetA2WithModel' => json_encode([
                'reference' => static::ASSET_A2_REFERENCE,
                'ssp_models' => [['id_ssp_model' => static::MODEL_M2_ID]],
            ]),
            'assetA3NoModel' => json_encode([
                'reference' => static::ASSET_A3_REFERENCE,
            ]),
            'assetB1WithModel' => json_encode([
                'reference' => 'B1',
                'ssp_models' => [['id_ssp_model' => static::MODEL_M2_ID]],
            ]),
            'modelM1' => json_encode([
                'id_model' => static::MODEL_M1_ID,
                'whitelist_ids' => [static::PRODUCT_LIST_ID],
            ]),
            'modelM2' => json_encode([
                'id_model' => static::MODEL_M2_ID,
                'whitelist_ids' => [999],
            ]),
        ];
    }

    public function setupStorageClientMock(MockObject $storageClient, array $storageData): void
    {
        $storageClient
            ->method('get')
            ->willReturnCallback(function ($key) use ($storageData) {
                if (strpos($key, 'ssp_asset:' . static::ASSET_A1_REFERENCE) !== false) {
                    return $storageData['assetA1WithModel'];
                }
                if (strpos($key, 'ssp_asset:' . static::ASSET_A2_REFERENCE) !== false) {
                    return $storageData['assetA2WithModel'];
                }
                if (strpos($key, 'ssp_asset:' . static::ASSET_A3_REFERENCE) !== false) {
                    return $storageData['assetA3NoModel'];
                }
                if (strpos($key, 'ssp_asset:B1') !== false) {
                    return $storageData['assetB1WithModel'];
                }
                if (strpos($key, 'ssp_model:' . static::MODEL_M1_ID) !== false) {
                    return $storageData['modelM1'];
                }
                if (strpos($key, 'ssp_model:' . static::MODEL_M2_ID) !== false) {
                    return $storageData['modelM2'];
                }

                return null;
            });
    }

    public function setupProductListStorageClientMock(MockObject $productListClient): void
    {
        $productConcreteProductListStorage = (new ProductConcreteProductListStorageTransfer())
            ->setIdWhitelists([static::PRODUCT_LIST_ID]);

        $productListClient
            ->method('findProductConcreteProductListStorage')
            ->with(static::PRODUCT_P1_ID)
            ->willReturn($productConcreteProductListStorage);
    }
}
