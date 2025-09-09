<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\MerchantProductDataImport;

use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig as SprykerMerchantProductDataImportConfig;

class MerchantProductDataImportConfig extends SprykerMerchantProductDataImportConfig
{
    /**
     * @var list<string>
     */
    protected const POSSIBLE_CSV_HEADERS = [
        'abstract_sku',
        'is_active',
        'concrete_sku',
        'store_relations',
        'product_abstract.categories',
        'product_abstract.tax_set_name',
        'product_abstract.new_from',
        'product_abstract.new_to',
        'product.is_quantity_splittable',
        'product.assigned_product_type',
    ];

    /**
     * @var list<string>
     */
    protected const POSSIBLE_CSV_LOCALE_HEADERS = [
        'product_abstract.name.{locale}',
        'product_abstract.description.{locale}',
        'product_abstract.meta_title.{locale}',
        'product_abstract.meta_description.{locale}',
        'product_abstract.meta_keywords.{locale}',
        'product_abstract.url.{locale}',
        'product.name.{locale}',
        'product.description.{locale}',
        'product.is_searchable.{locale}',
    ];

    /**
     * @var list<string>
     */
    protected const POSSIBLE_CSV_ATTRIBUTE_HEADERS = [
        'product.{attribute}',
        'product.{attribute}.{locale}',
    ];

    /**
     * @var list<string>
     */
    protected const POSSIBLE_CSV_STOCK_HEADERS = [
        'product.{stock}.quantity',
        'product.{stock}.is_never_out_of_stock',
    ];

    /**
     * @var list<string>
     */
    protected const POSSIBLE_CSV_PRICE_HEADERS = [
        'product_price.{store}.default.{currency}.value_net',
        'product_price.{store}.default.{currency}.value_gross',
        'abstract_product_price.{store}.default.{currency}.value_net',
        'abstract_product_price.{store}.default.{currency}.value_gross',
    ];

    /**
     * @var list<string>
     */
    protected const POSSIBLE_CSV_IMAGE_HEADERS = [
        'product_image.DEFAULT.default.sort_order',
        'product_image.DEFAULT.default.external_url_large',
        'product_image.DEFAULT.default.external_url_small',
        'abstract_product_image.DEFAULT.default.sort_order',
        'abstract_product_image.DEFAULT.default.external_url_small',
        'abstract_product_image.DEFAULT.default.external_url_large',
        'abstract_product_image.{locale}.default.sort_order',
        'abstract_product_image.{locale}.default.external_url_small',
        'abstract_product_image.{locale}.default.external_url_large',
    ];
}
