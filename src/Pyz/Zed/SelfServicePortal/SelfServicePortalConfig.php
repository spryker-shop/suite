<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\SelfServicePortal;

use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig as SprykerSelfServicePortalConfig;

class SelfServicePortalConfig extends SprykerSelfServicePortalConfig
{
    /**
     * @return string
     */
    public function getDefaultMerchantReference(): string
    {
        return 'MER000001';
    }

    /**
     * @var string
     */
    protected const MODULE_NAME = 'self-service-portal';

    /**
     * @return array<string>
     */
    public function getInquiryStatusClassMap(): array
    {
        return [
            'approved' => 'label-success',
            'rejected' => 'label-danger',
            'pending' => 'label-warning',
            'canceled' => 'label-default',
            'in_review' => 'label-primary',
        ];
    }

    /**
     * @return array<string>
     */
    public function getAssetStatusClassMap(): array
    {
        return [
            'pending' => 'label-warning',
            'in_review' => 'label-primary',
            'approved' => 'label-success',
            'deactivated' => 'label-danger',
        ];
    }

    /**
     * @return string
     */
    public function getInquiryPendingStatus(): string
    {
        return 'pending';
    }
}
