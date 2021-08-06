<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\ProductRelation\Presentation;

use PyzTest\Zed\ProductRelation\PageObject\ProductRelationCreatePage;
use PyzTest\Zed\ProductRelation\ProductRelationPresentationTester;
use Spryker\Shared\ProductRelation\ProductRelationTypes;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group ProductRelation
 * @group Presentation
 * @group ProductRelationCreateRelationCest
 * Add your own group annotations below this line
 */
class ProductRelationCreateRelationCest
{
    /**
     * @param \PyzTest\Zed\ProductRelation\ProductRelationPresentationTester $i
     *
     * @return void
     */
    public function testICanCreateProductRelation(ProductRelationPresentationTester $i)
    {
        $i->wantTo('I want to create up selling relation');
        $i->expect('relation is persisted, exported to yves and carousel component is visible');

        $i->amLoggedInUser();

        $i->amOnPage(ProductRelationCreatePage::URL);
        $key = uniqid('key-', false);
        $i->fillField('//*[@id="product_relation_productRelationKey"]', $key);

        $i->selectRelationType(ProductRelationTypes::TYPE_RELATED_PRODUCTS);
        $i->filterProductsByName('Samsung Bundle');
        $i->wait(5);
        $i->selectProduct(214);

        $i->switchToAssignProductsTab();

        $i->selectProductRule('product_sku', 'equal', '123');

        $i->clickSaveButton();

        $i->seeInPageSource(sprintf('%s %s', ProductRelationCreatePage::EDIT_PRODUCT_RELATION_TEXT, $key));
    }
}
