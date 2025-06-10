<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\VolumeDataGeneration;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class VolumeDataGenerationConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const ALL_ENTITIES_GENERATED_MESSAGE = 'No more entities to process';

    /**
     * @var string
     */
    public const NO_TESTS_EXECUTED = 'No tests executed';

    /**
     * @var string
     */
    public const GENERATION_RESULT_TEXT = 'Generation result is:';

    /**
     * Specification:
     * - Returns a list of entity types for which volume data generation is available.
     * - Entity type is defined as a @group annotation in the test class.
     * - Order of the array defines the order of entity generation. It's important to keep the order to avoid errors during generation.
     * - Check tests in tests/VolumeDataGenerationTest/Zed/Ssp to define the order of generation.
     *
     * @return array<string>
     */
    public function getEntityTypes(): array
    {
        return [
            'Companies',
            'SspAssets',
            'SspAssetInquiries',
            'GeneralSspInquiries',
            'Files',
            'SspAssetFiles',
            'CompanyFiles',
            'Services',
            'ServiceSspAssetOrder',
        ];
    }
}
