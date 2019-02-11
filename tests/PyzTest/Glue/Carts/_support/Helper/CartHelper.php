<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\Helper;

use Codeception\Module;
use SprykerTest\Glue\Testify\Helper\GlueRest;
use SprykerTest\Shared\Testify\Helper\ModuleLocatorTrait;

class CartHelper extends Module
{
    use ModuleLocatorTrait;

    /**
     * @var \SprykerTest\Glue\Testify\Helper\GlueRest
     */
    private $glueRest;

    /**
     * @inheritdoc
     */
    public function _initialize(): void
    {
        parent::_initialize();

        $this->glueRest = $this->locateModule(GlueRest::class);
    }

    /**
     * @part json
     *
     * @return string
     */
    public function haveCart(): string
    {
        $this->glueRest->sendGET('carts');

        $jsonResponse = $this->glueRest->grabResponseJson();

        return $jsonResponse['data'][0]['id'];
    }
}
