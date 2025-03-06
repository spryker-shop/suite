<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\SspInquiryManagement;

use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig as SprykerSspInquiryManagementConfig;

class SspInquiryManagementConfig extends SprykerSspInquiryManagementConfig
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getSspInquiryStatusClassMap(): array
    {
        return [
            'approved' => 'label-success',
            'rejected' => 'label-danger',
            'pending' => 'label-warning',
            'canceled' => 'label-default',
            'in_review' => 'label-primary',
        ];
    }
}
