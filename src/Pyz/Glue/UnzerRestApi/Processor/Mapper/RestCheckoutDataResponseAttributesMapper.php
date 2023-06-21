<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\UnzerRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use SprykerEco\Glue\UnzerRestApi\Processor\Mapper\RestCheckoutDataResponseAttributesMapper as SprykerEcoRestCheckoutDataResponseAttributesMapper;

class RestCheckoutDataResponseAttributesMapper extends SprykerEcoRestCheckoutDataResponseAttributesMapper
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function mapRestCheckoutDataTransferToRestCheckoutDataResponseAttributesTransfer(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $unzerCredentialsTransfer = $restCheckoutDataTransfer->getQuoteOrFail()->getUnzerCredentials();
        if ($unzerCredentialsTransfer === null || $unzerCredentialsTransfer->getUnzerKeypair() === null) {
            return $restCheckoutDataResponseAttributesTransfer;
        }

        $unzerPublicKey = $restCheckoutDataTransfer->getQuoteOrFail()
            ->getUnzerCredentialsOrFail()
            ->getUnzerKeypairOrFail()
            ->getPublicKey();

        return $restCheckoutDataResponseAttributesTransfer->setUnzerPublicKey($unzerPublicKey);
    }
}
