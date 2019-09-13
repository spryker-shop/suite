<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ExampleProductSalePage\Persistence;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Pyz\Shared\ExampleProductSalePage\ExampleProductSalePageConfig;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Pyz\Zed\ExampleProductSalePage\Persistence\ExampleProductSalePagePersistenceFactory getFactory()
 */
class ExampleProductSalePageQueryContainer extends AbstractQueryContainer implements ExampleProductSalePageQueryContainerInterface
{
    /**
     * @api
     *
     * @param string $labelName
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelByName($labelName)
    {
        return $this->getFactory()
            ->getProductLabelQueryContainer()
            ->queryProductLabelByName($labelName);
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryRelationsBecomingInactive($idProductLabel)
    {
        return $this->getFactory()
            ->getProductLabelQueryContainer()
            ->queryProductAbstractRelationsByIdProductLabel($idProductLabel)
            ->useSpyProductAbstractQuery(null, Criteria::LEFT_JOIN)
                ->usePriceProductQuery(null, Criteria::LEFT_JOIN)
                    ->joinPriceType('priceType', Criteria::INNER_JOIN)
                    ->addJoinCondition('priceType', 'priceType.name = ?', ExampleProductSalePageConfig::PRICE_TYPE_ORIGINAL)
                    ->joinPriceProductStore()
                        ->usePriceProductStoreQuery(null, Criteria::INNER_JOIN)
                            ->where(
                                sprintf(
                                    '(%1$s IS NULL OR %2$s IS NULL)',
                                    SpyPriceProductStoreTableMap::COL_GROSS_PRICE,
                                    SpyPriceProductStoreTableMap::COL_NET_PRICE
                                )
                            )
                            ->joinPriceProductDefault()
                                ->usePriceProductDefaultQuery(null, Criteria::INNER_JOIN)
                                ->endUse()
                        ->endUse()
                ->endUse()
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryRelationsBecomingActive($idProductLabel)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->usePriceProductQuery()
                ->joinPriceType('priceType', Criteria::INNER_JOIN)
                ->addJoinCondition(
                    'priceType',
                    'priceType.name = ?',
                    ExampleProductSalePageConfig::PRICE_TYPE_ORIGINAL
                )
                ->joinPriceProductStore()
                ->usePriceProductStoreQuery(null, Criteria::INNER_JOIN)
                    ->joinPriceProductDefault()
                        ->usePriceProductDefaultQuery(null, Criteria::INNER_JOIN)
                        ->endUse()
                ->endUse()
            ->endUse()
            ->useSpyProductLabelProductAbstractQuery('rel', Criteria::LEFT_JOIN)
                ->filterByFkProductLabel(null, Criteria::ISNULL)
            ->endUse()
            ->addJoinCondition('rel', sprintf('rel.fk_product_label = %d', $idProductLabel))
            ->where(
                sprintf(
                    '(%1$s IS NOT NULL OR %2$s IS NOT NULL)',
                    SpyPriceProductStoreTableMap::COL_GROSS_PRICE,
                    SpyPriceProductStoreTableMap::COL_NET_PRICE
                )
            )
            ->addGroupByColumn('id_product_abstract');
    }
}
