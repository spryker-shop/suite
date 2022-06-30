<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CartPage\Controller;

use Generated\Shared\Transfer\CartPageViewArgumentsTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Shared\CartPage\Plugin\AddCartItemPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\RemoveCartItemPermissionPlugin;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 * @method \SprykerShop\Yves\CartPage\CartPageConfig getConfig()
 */
class CartAsyncController extends AbstractController
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const KEY_MESSAGES = 'messages';

    /**
     * @var string
     */
    public const MESSAGE_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @var string
     */
    protected const FLASH_MESSAGE_LIST_TEMPLATE_PATH = '@ShopUi/components/organisms/flash-message-list/flash-message-list.twig';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeAction(Request $request, string $sku)
    {
        // CsrfToken validation is skipped

        if (!$this->canPerformCartItemAction(RemoveCartItemPermissionPlugin::KEY)) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->jsonResponse([
                static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            ]);
        }

        $this->getFactory()
            ->getCartClient()
            ->removeItem($sku, $request->get('groupKey', null));

        $messageTransfers = $this->getFactory()
            ->getZedRequestClient()
            ->getLastResponseErrorMessages();

        if ($messageTransfers) {
            $this->addErrorMessages($messageTransfers);

            return $this->jsonResponse([
                static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            ]);
        }

        return $this->view(
            $this->executeIndexAction($request->get('selectedAttributes', [])),
            $this->getFactory()->getCartPageWidgetPlugins(),
            '@CartPage/views/cart-async/cart-async.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function quickAddAction(Request $request)
    {
        // CsrfToken validation is skipped

        $sku = $request->get('sku');
        $quantity = $request->get('quantity', 1);

        if (!$this->canPerformCartItemAction(AddCartItemPermissionPlugin::KEY)) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->jsonResponse([
                static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            ]);
        }

        $itemTransfer = (new ItemTransfer())
            ->setSku($sku)
            ->setQuantity($quantity)
            ->addNormalizableField('quantity')
            ->setGroupKeyPrefix(uniqid('', true));

        $itemTransfer = $this->executePreAddToCartPlugins($itemTransfer, $request->query->all());

        $this->getFactory()
            ->getCartClient()
            ->addItem($itemTransfer);

        $messageTransfers = $this->getFactory()
            ->getZedRequestClient()
            ->getLastResponseErrorMessages();

        if ($messageTransfers) {
            $this->addErrorMessages($messageTransfers);

            return $this->jsonResponse([
                static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            ]);
        }

        return $this->view(
            $this->executeIndexAction($request->get('selectedAttributes', [])),
            $this->getFactory()->getCartPageWidgetPlugins(),
            '@CartPage/views/cart-async/cart-async.twig',
        );
    }

    /**
     * @param array $selectedAttributes
     *
     * @return array<string, mixed>
     */
    protected function executeIndexAction(array $selectedAttributes = []): array
    {
        $cartPageViewArgumentsTransfer = new CartPageViewArgumentsTransfer();
        $cartPageViewArgumentsTransfer->setLocale($this->getLocale())
            ->setSelectedAttributes($selectedAttributes);

        $viewData = $this->getFactory()->createCartPageView()->getViewData($cartPageViewArgumentsTransfer);

        $viewData['isCartItemsViaAjaxLoadEnabled'] = false;
        $viewData['isUpsellingProductsViaAjaxEnabled'] = false;

        return $viewData;
    }

    /**
     * @param string $permissionPluginKey
     *
     * @return bool
     */
    protected function canPerformCartItemAction(string $permissionPluginKey): bool
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        if ($quoteTransfer->getCustomer() === null) {
            return true;
        }

        if ($quoteTransfer->getCustomer()->getCompanyUserTransfer() === null) {
            return true;
        }

        if ($this->can($permissionPluginKey)) {
            return true;
        }

        return false;
    }

    /**
     * @param array<\Generated\Shared\Transfer\MessageTransfer> $messageTransfers
     *
     * @return void
     */
    protected function addErrorMessages(array $messageTransfers): void
    {
        foreach ($messageTransfers as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\MessageTransfer> $messageTransfers
     *
     * @return void
     */
    protected function addSuccessMessages(array $messageTransfers): void
    {
        foreach ($messageTransfers as $messageTransfer) {
            $this->addSuccessMessage($messageTransfer->getValue());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function executePreAddToCartPlugins(ItemTransfer $itemTransfer, array $params): ItemTransfer
    {
        foreach ($this->getFactory()->getPreAddToCartPlugins() as $preAddToCartPlugin) {
            $itemTransfer = $preAddToCartPlugin->preAddToCart($itemTransfer, $params);
        }

        return $itemTransfer;
    }
}
