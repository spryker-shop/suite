<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql;

class ProductAbstractMariaDbSql implements ProductAbstractSqlInterface
{
    /**
     * @return string
     */
    public function createAbstractProductSQL(): string
    {
        return '
            INSERT INTO spy_product_abstract (
                id_product_abstract,
                sku,
                attributes,
                fk_tax_set,
                color_code,
                new_from,
                new_to,
                created_at,
                updated_at
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
                ),
                records AS (
                    SELECT
                      input.abstract_sku,
                      input.attributes,
                      input.fkTaxSet,
                      input.colorCode,
                      input.newFrom,
                      input.newTo,
                      id_product_abstract as idProductAbstract
                    FROM (
                       SELECT DISTINCT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.abstractSkues, \',\', n.digit + 1), \',\', -1), \'\') as abstract_sku,
                            REPLACE(NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.attributeses, \',\', n.digit + 1), \',\', -1), \'\'), \'|\', \',\') as attributes,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fkTaxSets, \',\', n.digit + 1), \',\', -1), \'\') as fkTaxSet,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.colorCodes, \',\', n.digit + 1), \',\', -1), \'\') as colorCode,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.newFroms, \',\', n.digit + 1), \',\', -1), \'\') as newFrom,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.newTos, \',\', n.digit + 1), \',\', -1), \'\') as newTo
                       FROM (
                            SELECT ? as abstractSkues,
                                   ? as attributeses,
                                   ? as fkTaxSets,
                                   ? as colorCodes,
                                   ? as newFroms,
                                   ? as newTos
                       ) temp
                       INNER JOIN n
                          ON LENGTH(REPLACE(abstractSkues, \',\', \'\')) <= LENGTH(abstractSkues) - n.digit
                            AND LENGTH(REPLACE(attributeses, \',\', \'\')) <= LENGTH(attributeses) - n.digit
                            AND LENGTH(REPLACE(fkTaxSets, \',\', \'\')) <= LENGTH(fkTaxSets) - n.digit
                            AND LENGTH(REPLACE(colorCodes, \',\', \'\')) <= LENGTH(colorCodes) - n.digit
                            AND LENGTH(REPLACE(newFroms, \',\', \'\')) <= LENGTH(newFroms) - n.digit
                            AND LENGTH(REPLACE(newTos, \',\', \'\')) <= LENGTH(newTos) - n.digit
                    ) input
                    LEFT JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
                )
                (
                  SELECT
                    idProductAbstract,
                    abstract_sku,
                    attributes,
                    fkTaxSet,
                    colorCode,
                    newFrom,
                    newTo,
                    now(),
                    now()
                  FROM records
                )
            ON DUPLICATE KEY UPDATE sku = records.abstract_sku,
              attributes = records.attributes,
              updated_at = now(),
              fk_tax_set = records.fkTaxSet,
              color_code = records.colorCode,
              new_from = records.newFrom,
              new_to = records.newTo
            RETURNING id_product_abstract, sku
        ';
    }

    /**
     * @return string
     */
    public function createAbstractProductLocalizedAttributesSQL(): string
    {
        return '
            INSERT INTO spy_product_abstract_localized_attributes (
                    id_abstract_attributes,
                    name,
                    description,
                    meta_title,
                    meta_description,
                    meta_keywords,
                    attributes,
                    fk_locale,
                    fk_product_abstract
                )
                WITH RECURSIVE n(digit) AS (
                    SELECT 0 as digit
                    UNION ALL
                    SELECT 1 + digit FROM n WHERE digit < ?
                    ),
                    records AS (
                        SELECT
                          input.abstract_sku,
                          input.name,
                          input.description,
                          input.metaTitle,
                          input.metaDescription,
                          input.metaKeywords,
                          input.idLocale,
                          input.attributes,
                          id_product_abstract,
                          id_abstract_attributes
                        FROM (
                           SELECT DISTINCT
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.abstractSkues, \',\', n.digit + 1), \',\', -1), \'\') as abstract_sku,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.names, \',\', n.digit + 1), \',\', -1), \'\') as name,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.descriptions, \',\', n.digit + 1), \',\', -1), \'\') as description,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.metaTitles, \',\', n.digit + 1), \',\', -1), \'\') as metaTitle,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.metaDescriptions, \',\', n.digit + 1), \',\', -1), \'\') as metaDescription,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.metaKeywordses, \',\', n.digit + 1), \',\', -1), \'\') as metaKeywords,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.idLocales, \',\', n.digit + 1), \',\', -1), \'\') as idLocale,
                                REPLACE(NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.attributeses, \',\', n.digit + 1), \',\', -1), \'\'), \'|\', \',\') as attributes
                           FROM (
                                SELECT ? as abstractSkues,
                                       ? as names,
                                       ? as descriptions,
                                       ? as metaTitles,
                                       ? as metaDescriptions,
                                       ? as metaKeywordses,
                                       ? as idLocales,
                                       ? as attributeses
                           ) temp
                           INNER JOIN n
                              ON LENGTH(REPLACE(abstractSkues, \',\', \'\')) <= LENGTH(abstractSkues) - n.digit
                                AND LENGTH(REPLACE(names, \',\', \'\')) <= LENGTH(names) - n.digit
                                AND LENGTH(REPLACE(descriptions, \',\', \'\')) <= LENGTH(descriptions) - n.digit
                                AND LENGTH(REPLACE(metaTitles, \',\', \'\')) <= LENGTH(metaTitles) - n.digit
                                AND LENGTH(REPLACE(metaDescriptions, \',\', \'\')) <= LENGTH(metaDescriptions) - n.digit
                                AND LENGTH(REPLACE(metaKeywordses, \',\', \'\')) <= LENGTH(metaKeywordses) - n.digit
                                AND LENGTH(REPLACE(idLocales, \',\', \'\')) <= LENGTH(idLocales) - n.digit
                                AND LENGTH(REPLACE(attributeses, \',\', \'\')) <= LENGTH(attributeses) - n.digit
                        ) input
                        INNER JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
                        LEFT JOIN spy_product_abstract_localized_attributes
                            ON (
                                spy_product_abstract_localized_attributes.fk_product_abstract = id_product_abstract
                                AND spy_product_abstract_localized_attributes.fk_locale = input.idLocale
                            )
                    )
                    (
                      SELECT
                        id_abstract_attributes,
                        name,
                        description,
                        metaTitle,
                        metaDescription,
                        metaKeywords,
                        attributes,
                        idLocale,
                        id_product_abstract
                      FROM records
                    )
                ON DUPLICATE KEY UPDATE updated_at = now(),
                  name = records.name,
                  description = records.description,
                  meta_title = records.metaTitle,
                  meta_description = records.metaDescription,
                  meta_keywords = records.metaKeywords,
                  attributes = records.attributes
            ';
    }

    /**
     * @return string
     */
    public function createAbstractProductCategoriesSQL(): string
    {
        return '
            INSERT INTO spy_product_category (
                    id_product_category,
                    product_order,
                    fk_category,
                    fk_product_abstract
                )
                WITH RECURSIVE n(digit) AS (
                    SELECT 0 as digit
                    UNION ALL
                    SELECT 1 + digit FROM n WHERE digit < ?
                    ),
                    records AS (
                        SELECT
                            input.abstract_sku,
                            input.productOrder,
                            input.IdCategory,
                            id_product_category,
                            id_product_abstract
                        FROM (
                           SELECT DISTINCT
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.abstractSkus, \',\', n.digit + 1), \',\', -1), \'\') as abstract_sku,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.productOrders, \',\', n.digit + 1), \',\', -1), \'\') as productOrder,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.IdsCategory, \',\', n.digit + 1), \',\', -1), \'\') as IdCategory
                           FROM (
                                SELECT ? as abstractSkus,
                                       ? as productOrders,
                                       ? as IdsCategory
                           ) temp
                           INNER JOIN n
                              ON LENGTH(REPLACE(abstractSkus, \',\', \'\')) <= LENGTH(abstractSkus) - n.digit
                                AND LENGTH(REPLACE(productOrders, \',\', \'\')) <= LENGTH(productOrders) - n.digit
                                AND LENGTH(REPLACE(IdsCategory, \',\', \'\')) <= LENGTH(IdsCategory) - n.digit
                        ) input
                        INNER JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
                        LEFT JOIN spy_product_category ON (
                            spy_product_category.fk_product_abstract = id_product_abstract
                            AND spy_product_category.fk_category = input.IdCategory
                        )
                    )
                    (
                      SELECT
                        id_product_category,
                        productOrder,
                        IdCategory,
                        id_product_abstract
                      FROM records
                    )
                ON DUPLICATE KEY UPDATE
                    product_order = records.productOrder,
                    fk_category = records.idCategory
                RETURNING fk_product_abstract as id_product_abstract
            ';
    }

    /**
     * @return string
     */
    public function createAbstractProductUrlsSQL(): string
    {
        return '
            INSERT INTO spy_url (
                    id_url,
                    fk_resource_product_abstract,
                    fk_locale,
                    url
                )
                WITH RECURSIVE n(digit) AS (
                    SELECT 0 as digit
                    UNION ALL
                    SELECT 1 + digit FROM n WHERE digit < ?
                    ),
                    records AS (
                        SELECT
                            input.abstract_sku,
                            input.idLocale,
                            input.url,
                            id_url as idUrl,
                            id_product_abstract
                        FROM (
                           SELECT DISTINCT
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.abstractSkus, \',\', n.digit + 1), \',\', -1), \'\') as abstract_sku,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.idsLocale, \',\', n.digit + 1), \',\', -1), \'\') as idLocale,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.urls, \',\', n.digit + 1), \',\', -1), \'\') as url
                           FROM (
                                SELECT ? as abstractSkus,
                                       ? as idsLocale,
                                       ? as urls
                           ) temp
                           INNER JOIN n
                              ON LENGTH(REPLACE(abstractSkus, \',\', \'\')) <= LENGTH(abstractSkus) - n.digit
                                AND LENGTH(REPLACE(idsLocale, \',\', \'\')) <= LENGTH(idsLocale) - n.digit
                                AND LENGTH(REPLACE(urls, \',\', \'\')) <= LENGTH(urls) - n.digit
                        ) input
                        INNER JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
                        LEFT JOIN spy_url ON (
                            spy_url.fk_resource_product_abstract = id_product_abstract
                            AND spy_url.fk_locale = input.idLocale
                        )
                    )
                    (
                      SELECT
                        idUrl,
                        id_product_abstract,
                        idLocale,
                        url
                      FROM records
                    )
                ON DUPLICATE KEY UPDATE url = records.url
                RETURNING id_url, fk_resource_product_abstract as id_product_abstract
            ';
    }
}
