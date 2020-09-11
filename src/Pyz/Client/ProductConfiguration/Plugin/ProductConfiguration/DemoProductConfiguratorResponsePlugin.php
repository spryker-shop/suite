<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductConfiguration\Plugin\ProductConfiguration;

use Generated\Shared\Transfer\ItemReplaceTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Client\Cart\CartClient;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;
use Spryker\Shared\ProductConfiguration\ProductConfigurationConfig;

/**
 * @TODO this class would be fully reworked in 2nd phase. Will keep it for now as demo version.
 */
class DemoProductConfiguratorResponsePlugin extends AbstractPlugin implements ProductConfiguratorResponsePluginInterface
{
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_SKU_IS_NOT_PROVIDED = 'product_configuration.validation.error.sku_is_not_provided';
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_GROUP_KEY_IS_NOT_PROVIDED = 'product_configuration.validation.error.group_key_is_not_provided';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $this->assertMandatoryFields($productConfiguratorResponseTransfer);
        $productConfiguratorResponseProcessorResponseTransfer = $this->validateResponse($productConfiguratorResponseTransfer);

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        $this->storeProductConfigurationInstance($productConfiguratorResponseTransfer);

        return $this->replaceItemInQuote(
            $productConfiguratorResponseTransfer,
            $productConfiguratorResponseProcessorResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function validateResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setProductConfiguratorResponse($productConfiguratorResponseTransfer)
            ->setIsSuccessful(true);

        if ($productConfiguratorResponseTransfer->getSourceType() === ProductConfigurationConfig::SOURCE_TYPE_PDP && !$productConfiguratorResponseTransfer->getSku()) {
            return $this->addErrorMessage(
                $productConfiguratorResponseProcessorResponseTransfer,
                static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_SKU_IS_NOT_PROVIDED
            );
        }

        if (
            $productConfiguratorResponseTransfer->getSourceType() === ProductConfigurationConfig::SOURCE_TYPE_CART &&
            !$productConfiguratorResponseTransfer->getItemGroupKey() && !$productConfiguratorResponseTransfer->getSku()
        ) {
            return $this->addErrorMessage(
                $productConfiguratorResponseProcessorResponseTransfer,
                static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_GROUP_KEY_IS_NOT_PROVIDED
            );
        }

        return $productConfiguratorResponseProcessorResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return void
     */
    protected function storeProductConfigurationInstance(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
    ): void {
        if (!$productConfiguratorResponseTransfer->getSku() || $productConfiguratorResponseTransfer->getSourceType() !== ProductConfigurationConfig::SOURCE_TYPE_PDP) {
            return;
        }

        (new ProductConfigurationStorageClient())->storeProductConfigurationInstanceBySku(
            $productConfiguratorResponseTransfer->getSku(),
            $productConfiguratorResponseTransfer->getProductConfigurationInstance()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function replaceItemInQuote(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        if (
            $productConfiguratorResponseTransfer->getSourceType() !== ProductConfigurationConfig::SOURCE_TYPE_CART ||
            (!$productConfiguratorResponseTransfer->getItemGroupKey() && !$productConfiguratorResponseTransfer->getSku())
        ) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        $cartClient = new CartClient();
        $quoteTransfer = $cartClient->getQuote();

        $itemToBeReplaced = $cartClient->findQuoteItem(
            $quoteTransfer,
            $productConfiguratorResponseTransfer->getSku(),
            $productConfiguratorResponseTransfer->getItemGroupKey()
        );
        $newItem = $itemToBeReplaced ? (clone $itemToBeReplaced)->setProductConfigurationInstance($productConfiguratorResponseTransfer->getProductConfigurationInstance()) : null;

        $itemReplaceTransfer = (new ItemReplaceTransfer())
            ->setItemToBeReplaced($itemToBeReplaced)
            ->setNewItem($newItem)
            ->setQuote($quoteTransfer);

        $quoteResponseTransfer = $cartClient->replaceItem($itemReplaceTransfer);

        return $productConfiguratorResponseProcessorResponseTransfer
            ->setIsSuccessful($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return void
     */
    protected function assertMandatoryFields(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): void
    {
        $productConfiguratorResponseTransfer
            ->requireSourceType()
            ->requireCheckSum()
            ->requireTimestamp()
            ->requireProductConfigurationInstance()
            ->getProductConfigurationInstance()
            ->requireConfiguratorKey();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function addErrorMessage(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        string $message
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseProcessorResponseTransfer
            ->addMessage((new MessageTransfer())->setMessage($message))
            ->setIsSuccessful(false);

        return $productConfiguratorResponseProcessorResponseTransfer;
    }
}
