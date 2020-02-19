<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductRelationDataImport;

use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductRelationDataImport\ProductRelationDataImportDependencyProvider as SprykerProductRelationDataImportDependencyProvider;

class ProductRelationDataImportDependencyProvider extends SprykerProductRelationDataImportDependencyProvider
{
    public const FACADE_PRODUCT_RELATION = 'FACADE_PRODUCT_RELATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductRelationFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductRelationFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_RELATION, function (Container $container) {
            return $container->getLocator()->productRelation()->facade();
        });

        return $container;
    }
}
