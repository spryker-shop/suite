<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CommentWidget\Controller;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\CommentWidget\Controller\CommentWidgetAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class CommentAsyncController extends CommentWidgetAbstractController
{
    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID_ADD_COMMENT_FORM = 'add-comment-form';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_TOKEN = '_token';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_MESSAGE_FORM_CSRF_ERROR = 'form.csrf.error.text';

    /**
     * @var string
     */
    protected const KEY_MESSAGES = 'messages';

    /**
     * @var string
     */
    protected const FLASH_MESSAGE_LIST_TEMPLATE_PATH = '@ShopUi/components/organisms/flash-message-list/flash-message-list.twig';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addAction(Request $request)
    {
        $response = $this->executeAddAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(Request $request)
    {
        $response = $this->executeUpdateAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeAction(Request $request)
    {
        $response = $this->executeRemoveAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function executeAddAction(Request $request)
    {
        $tokenValue = (string)$request->get(static::REQUEST_PARAMETER_TOKEN);

        if (!$this->validateCsrfToken(static::CSRF_TOKEN_ID_ADD_COMMENT_FORM, $tokenValue)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_ERROR_MESSAGE_FORM_CSRF_ERROR);

            return $this->jsonResponse([
                static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            ]);
        }

        $commentTransfer = $this->createCommentTransferFromRequest($request);

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->fromArray($request->request->all(), true)
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentClient()
            ->addComment($commentRequestTransfer);

        if (!$commentThreadResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessages($commentThreadResponseTransfer->getMessages()->getArrayCopy());

            return $this->jsonResponse([
                static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            ]);
        }

        $this->executeCommentThreadAfterSuccessfulOperation($commentThreadResponseTransfer);
        $this->handleResponseMessages($commentThreadResponseTransfer);

        return $this->createCommentThreadView($commentThreadResponseTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function executeUpdateAction(Request $request)
    {
        // CsrfToken validation is skipped

        $commentTransfer = $this->createCommentTransferFromRequest($request);
        $commentRequestTransfer = (new CommentRequestTransfer())->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentClient()
            ->updateComment($commentRequestTransfer);

        if (!$commentThreadResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessages($commentThreadResponseTransfer->getMessages()->getArrayCopy());

            return $this->jsonResponse([
                static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            ]);
        }

        $this->executeCommentThreadAfterSuccessfulOperation($commentThreadResponseTransfer);
        $this->handleResponseMessages($commentThreadResponseTransfer);

        return $this->createCommentThreadView($commentThreadResponseTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function executeRemoveAction(Request $request)
    {
        // CsrfToken validation is skipped

        $commentTransfer = $this->createCommentTransferFromRequest($request);
        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentClient()
            ->removeComment($commentRequestTransfer);

        if (!$commentThreadResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessages($commentThreadResponseTransfer->getMessages()->getArrayCopy());

            return $this->jsonResponse([
                static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            ]);
        }

        $this->executeCommentThreadAfterSuccessfulOperation($commentThreadResponseTransfer);
        $this->handleResponseMessages($commentThreadResponseTransfer);

        return $this->createCommentThreadView($commentThreadResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return array
     */
    protected function preapreViewData(CommentThreadTransfer $commentThreadTransfer): array
    {
        return [
            'customer' => $this->getFactory()->getCustomerClient()->getCustomer(),
            'ownerId' => $commentThreadTransfer->getOwnerId(),
            'ownerType' => $commentThreadTransfer->getOwnerType(),
            'taggedComments' => $this->prepareTaggedComments($commentThreadTransfer),
            'availableCommentTags' => $this->getFactory()->getCommentClient()->getAvailableCommentTags(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return array<string, array<\Generated\Shared\Transfer\CommentTransfer>>
     */
    protected function prepareTaggedComments(CommentThreadTransfer $commentThreadTransfer): array
    {
        $taggedComments = [];

        foreach ($commentThreadTransfer->getComments() as $comment) {
            $taggedComments['all'][] = $comment;

            /** @var array<string> $tagNames */
            $tagNames = $comment->getTagNames();
            foreach ($tagNames as $tagName) {
                $taggedComments[$tagName][] = $comment;
            }
        }

        return $taggedComments;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    protected function createCommentTransferFromRequest(Request $request): CommentTransfer
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        return (new CommentTransfer())
            ->fromArray($request->request->all(), true)
            ->setCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    protected function createCommentThreadView(CommentThreadResponseTransfer $commentThreadResponseTransfer): View
    {
        return $this->view(
            $this->preapreViewData($commentThreadResponseTransfer->getCommentThreadOrFail()),
            [],
            '@CommentWidget/views/comment-thread-async/comment-thread-async.twig',
        );
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
     * @param string $tokenId
     * @param string $value
     *
     * @return bool
     */
    protected function validateCsrfToken(string $tokenId, string $value): bool
    {
        $csrfToken = new CsrfToken($tokenId, $value);

        return $this->getFactory()->getCsrfTokenManager()->isTokenValid($csrfToken);
    }
}
