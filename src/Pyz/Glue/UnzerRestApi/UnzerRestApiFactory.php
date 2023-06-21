<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\UnzerRestApi;

use Pyz\Glue\UnzerRestApi\Processor\Mapper\RestCheckoutDataResponseAttributesMapper;
use SprykerEco\Glue\UnzerRestApi\Processor\Mapper\RestCheckoutDataResponseAttributesMapperInterface;
use SprykerEco\Glue\UnzerRestApi\UnzerRestApiFactory as SprykerEcoUnzerRestApiFactory;

class UnzerRestApiFactory extends SprykerEcoUnzerRestApiFactory
{
    /**
     * @return \SprykerEco\Glue\UnzerRestApi\Processor\Mapper\RestCheckoutDataResponseAttributesMapperInterface
     */
    public function createRestCheckoutDataResponseAttributesMapper(): RestCheckoutDataResponseAttributesMapperInterface
    {
        return new RestCheckoutDataResponseAttributesMapper();
    }
}
