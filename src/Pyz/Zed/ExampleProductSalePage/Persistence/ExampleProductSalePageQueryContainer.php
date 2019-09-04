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
//        return $this->getFactory()
//            ->getProductLabelQueryContainer()
//            ->queryProductAbstractRelationsByIdProductLabel($idProductLabel)
//            ->useSpyProductAbstractQuery(null, Criteria::LEFT_JOIN)
//                ->usePriceProductQuery(null, Criteria::LEFT_JOIN)
//                    ->joinPriceType('priceType', Criteria::LEFT_JOIN)
//                    ->addJoinCondition('priceType', 'priceType.name = ?', ExampleProductSalePageConfig::PRICE_TYPE_ORIGINAL)
//                    ->filterByPrice(null, Criteria::ISNULL)
//                ->endUse()
//            ->endUse();
        // inner_join: spy_price_product_store <fk_Price_product> ??? gross or net or both ?? PriceProductModuleConfig
        // inner_join: spy_price_product_default <idPriceProductStore>
        // ?? Jeremy

        $query = $this->getFactory()
            ->getProductLabelQueryContainer()
            ->queryProductAbstractRelationsByIdProductLabel($idProductLabel)
            ->useSpyProductAbstractQuery(null, Criteria::LEFT_JOIN)
                ->usePriceProductQuery(null, Criteria::LEFT_JOIN)
                    ->joinPriceType('priceType', Criteria::INNER_JOIN)
                    ->addJoinCondition('priceType', 'priceType.name = ?', ExampleProductSalePageConfig::PRICE_TYPE_ORIGINAL)
        //                    ->filterByPrice(null, Criteria::ISNULL)
                    ->joinPriceProductStore()
                        ->usePriceProductStoreQuery(null, Criteria::INNER_JOIN)
                            ->condition(
                                "hasn't gross and net price",
                                sprintf('(%1$s IS NULL AND %2$s IS NULL)',
                                    SpyPriceProductStoreTableMap::COL_GROSS_PRICE,
                                    SpyPriceProductStoreTableMap::COL_NET_PRICE
                                )
                            )
                            ->condition(
                                "hasn't gross or net price",
                                sprintf('(%1$s IS NULL OR %2$s IS NULL)',
                                    SpyPriceProductStoreTableMap::COL_GROSS_PRICE,
                                    SpyPriceProductStoreTableMap::COL_NET_PRICE
                                )
                            )
                            ->where(
                                ["hasn't gross and net price", "hasn't gross or net price"],
                                Criteria::LOGICAL_OR, "hasn't prices"
                            )
                        ->endUse()
                ->endUse()
            ->endUse();

//        $params = $query->getParams();
//        dd($idProductLabel, $query->createSelectSql($params));
        return $query;
        // inner_join: spy_price_product_store <fk_Price_product> ??? gross or net or both ?? PriceProductModuleConfig
        // inner_join: spy_price_product_default <idPriceProductStore>
        // ?? Jeremy
        // ?? b2c, b2b - relation
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
        $query = $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->usePriceProductQuery()
                ->joinPriceType('priceType', Criteria::INNER_JOIN)
                ->addJoinCondition('priceType', 'priceType.name = ?', ExampleProductSalePageConfig::PRICE_TYPE_ORIGINAL)
//                ->filterByPrice(null, Criteria::ISNOTNULL)
                ->joinPriceProductStore()
                ->usePriceProductStoreQuery(null, Criteria::INNER_JOIN)
                ->endUse()
            ->endUse()
            ->useSpyProductLabelProductAbstractQuery('rel', Criteria::LEFT_JOIN)
                ->filterByFkProductLabel(null, Criteria::ISNULL)
            ->endUse()
            ->addJoinCondition('rel', sprintf('rel.fk_product_label = %d', $idProductLabel))
            ->condition(
                'has gross and net price',
                sprintf('(%1$s IS NOT NULL AND %2$s IS NOT NULL)',
                    SpyPriceProductStoreTableMap::COL_GROSS_PRICE,
                    SpyPriceProductStoreTableMap::COL_NET_PRICE
                )
            )
            ->condition(
                'has gross or net price',
                sprintf('(%1$s IS NOT NULL OR %2$s IS NOT NULL)',
                    SpyPriceProductStoreTableMap::COL_GROSS_PRICE,
                    SpyPriceProductStoreTableMap::COL_NET_PRICE
                )
            )
            ->where(['has gross and net price', 'has gross or net price'], Criteria::LOGICAL_OR, 'has prices')
            ->setDistinct();

        $params = $query->getParams();
        dd($idProductLabel, $query->createSelectSql($params));
        return $query;
    }
}
