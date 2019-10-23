<?php

namespace Pyz\Yves\SprykerShop.ChartWidgetExtension\Plugin\Provider;

use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class SprykerShop.ChartWidgetExtensionControllerProvider extends AbstractYvesControllerProvider
{

    const SPRYKERSHOP.CHARTWIDGETEXTENSION_INDEX = 'sprykershop.chartwidgetextension-index';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->createGetController('/spryker-shop.chart-widget-extension', static::SPRYKERSHOP.CHARTWIDGETEXTENSION_INDEX, 'SprykerShop.ChartWidgetExtension', 'Index', 'index');
    }

}
