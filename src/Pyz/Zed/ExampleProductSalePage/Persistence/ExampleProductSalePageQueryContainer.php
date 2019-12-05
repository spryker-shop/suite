<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ExampleProductSalePage\Persistence;

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
            ->distinct()
            ->useSpyProductAbstractQuery(null, Criteria::LEFT_JOIN)
                ->usePriceProductQuery('priceProductOrigin', Criteria::LEFT_JOIN)
                    ->joinPriceType('priceTypeOrigin', Criteria::INNER_JOIN)
                    ->addJoinCondition(
                        'priceTypeOrigin',
                        'priceTypeOrigin.name = ?',
                        ExampleProductSalePageConfig::PRICE_TYPE_ORIGINAL
                    )
                    ->usePriceProductStoreQuery('priceProductStoreOrigin', Criteria::LEFT_JOIN)
                        ->usePriceProductDefaultQuery('priceProductDefaultOriginal', Criteria::LEFT_JOIN)
                        ->endUse()
                    ->endUse()
                ->endUse()
                ->usePriceProductQuery('priceProductDefault', Criteria::INNER_JOIN)
                    ->joinPriceType('priceTypeDefault', Criteria::LEFT_JOIN)
                    ->addJoinCondition(
                        'priceTypeDefault',
                        'priceTypeDefault.name = ?',
                        ExampleProductSalePageConfig::PRICE_TYPE_DEFAULT
                    )
                    ->usePriceProductStoreQuery('priceProductStoreDefault', Criteria::LEFT_JOIN)
                        ->usePriceProductDefaultQuery('priceProductDefaultDefault', Criteria::LEFT_JOIN)
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->addAnd('priceProductDefaultOriginal.id_price_product_default', null, Criteria::ISNULL)
            ->addAnd('priceProductDefaultDefault.id_price_product_default', null, Criteria::ISNOTNULL)
            ->condition('cond1', 'priceProductStoreOrigin.fk_store = priceProductStoreDefault.fk_store')
            ->condition('cond2', 'priceProductStoreOrigin.fk_currency = priceProductStoreDefault.fk_currency')
            ->condition('cond3', 'priceProductStoreOrigin.gross_price < priceProductStoreDefault.gross_price')
            ->condition('cond4', 'priceProductStoreOrigin.gross_price  ' . Criteria::ISNULL)
            ->condition('cond5', 'priceProductStoreOrigin.net_price < priceProductStoreDefault.net_price')
            ->condition('cond6', 'priceProductStoreDefault.net_price  ' . Criteria::ISNULL)
            ->combine(['cond3', 'cond4', 'cond5', 'cond6'], Criteria::LOGICAL_OR, 'condOr')
            ->combine(['cond1', 'cond2'], Criteria::LOGICAL_AND, 'condAnd')
            ->where(['condOr', 'condAnd'], Criteria::LOGICAL_AND);
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
            ->distinct()
            ->usePriceProductQuery('priceProductOrigin', Criteria::LEFT_JOIN)
                ->joinPriceType('priceTypeOrigin', Criteria::INNER_JOIN)
                ->addJoinCondition(
                    'priceTypeOrigin',
                    'priceTypeOrigin.name = ?',
                    ExampleProductSalePageConfig::PRICE_TYPE_ORIGINAL
                )
                ->usePriceProductStoreQuery('priceProductStoreOrigin', Criteria::LEFT_JOIN)
                    ->usePriceProductDefaultQuery('priceProductDefaultOriginal', Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->usePriceProductQuery('priceProductDefault', Criteria::INNER_JOIN)
                ->joinPriceType('priceTypeDefault', Criteria::LEFT_JOIN)
                ->addJoinCondition(
                    'priceTypeDefault',
                    'priceTypeDefault.name = ?',
                    ExampleProductSalePageConfig::PRICE_TYPE_DEFAULT
                )
                ->usePriceProductStoreQuery('priceProductStoreDefault', Criteria::LEFT_JOIN)
                    ->usePriceProductDefaultQuery('priceProductDefaultDefault', Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->useSpyProductLabelProductAbstractQuery('rel', Criteria::LEFT_JOIN)
                ->filterByFkProductLabel(null, Criteria::ISNULL)
            ->endUse()
            ->addJoinCondition('rel', sprintf('rel.fk_product_label = %d', $idProductLabel))
            ->addAnd('rel.fk_product_label', null, Criteria::ISNULL)
            ->addAnd('priceProductDefaultOriginal.id_price_product_default', null, Criteria::ISNOTNULL)
            ->addAnd('priceProductDefaultDefault.id_price_product_default', null, Criteria::ISNOTNULL)
            ->addAnd('priceProductStoreOrigin.gross_price', null, Criteria::ISNOTNULL)
            ->addAnd('priceProductStoreOrigin.net_price', null, Criteria::ISNOTNULL)
            ->condition('cond1', 'priceProductStoreOrigin.fk_store = priceProductStoreDefault.fk_store')
            ->condition('cond2', 'priceProductStoreOrigin.fk_currency = priceProductStoreDefault.fk_currency')
            ->condition('cond3', 'priceProductStoreOrigin."gross_price" > priceProductStoreDefault."gross_price"')
            ->condition('cond4', 'priceProductStoreOrigin."net_price" > priceProductStoreDefault."net_price"')
            ->where([ 'cond1', 'cond2', 'cond3',  'cond4'], Criteria::LOGICAL_AND);
    }
}
