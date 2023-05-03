<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Spryker\Glue\GlueBackendApiApplicationGlueJsonApiConventionConnector\GlueBackendApiApplicationGlueJsonApiConventionConnectorDependencyProvider as SprykerGlueBackendApiApplicationGlueJsonApiConventionConnectorDependencyProvider;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;
use Spryker\Glue\PickingListsBackendApi\Plugin\GlueJsonApiConvention\PickingListItemsByPickingListsBackendResourceRelationshipPlugin;
use Spryker\Glue\PickingListsProductsBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\ConcreteProductsByPickingListItemsBackendResourceRelationshipPlugin;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\SalesOrdersByPickingListItemsBackendResourceRelationshipPlugin;
use Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\SalesShipmentsByPickingListsBackendResourceRelationshipPlugin;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\UsersByPickingListsBackendResourceRelationshipPlugin;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\WarehousesByPickingListsBackendResourceRelationshipPlugin;
use Spryker\Glue\ProductsBackendApi\ProductsBackendApiConfig;
use Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\ConcreteProductImageSetsByProductsBackendResourceRelationshipPlugin;
use Spryker\Glue\UsersBackendApi\Plugin\GlueJsonApiConvention\UserByWarehouseUserAssignmentBackendResourceRelationshipPlugin;
use Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiConfig;

class GlueBackendApiApplicationGlueJsonApiConventionConnectorDependencyProvider extends SprykerGlueBackendApiApplicationGlueJsonApiConventionConnectorDependencyProvider
{
    /**
     * @param \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface $resourceRelationshipCollection
     *
     * @return \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    protected function getResourceRelationshipPlugins(
        ResourceRelationshipCollectionInterface $resourceRelationshipCollection,
    ): ResourceRelationshipCollectionInterface {
        $resourceRelationshipCollection->addRelationship(
            WarehouseUsersBackendApiConfig::RESOURCE_TYPE_WAREHOUSE_USER_ASSIGNMENTS,
            new UserByWarehouseUserAssignmentBackendResourceRelationshipPlugin(),
        );

        $resourceRelationshipCollection->addRelationship(
            PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS,
            new PickingListItemsByPickingListsBackendResourceRelationshipPlugin(),
        );

        $resourceRelationshipCollection->addRelationship(
            PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_ITEMS,
            new ConcreteProductsByPickingListItemsBackendResourceRelationshipPlugin(),
        );

        $resourceRelationshipCollection->addRelationship(
            PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_ITEMS,
            new SalesOrdersByPickingListItemsBackendResourceRelationshipPlugin(),
        );

        $resourceRelationshipCollection->addRelationship(
            PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_ITEMS,
            new SalesShipmentsByPickingListsBackendResourceRelationshipPlugin(),
        );

        $resourceRelationshipCollection->addRelationship(
            PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS,
            new UsersByPickingListsBackendResourceRelationshipPlugin(),
        );

        $resourceRelationshipCollection->addRelationship(
            PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS,
            new WarehousesByPickingListsBackendResourceRelationshipPlugin(),
        );

        $resourceRelationshipCollection->addRelationship(
            ProductsBackendApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            new ConcreteProductImageSetsByProductsBackendResourceRelationshipPlugin(),
        );

        return $resourceRelationshipCollection;
    }
}