<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Glue\AuthRestApi\Controller;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Spryker\Glue\AuthRestApi\Controller\TokenResourceController as SprykerTokenResourceController;

/**
 * @method \Spryker\Glue\AuthRestApi\AuthRestApiFactory getFactory()
 */
class TokenResourceController extends SprykerTokenResourceController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postAction(Request $httpRequest): JsonResponse
    {
        $oauthRequestTransfer = (new OauthRequestTransfer())
            ->fromArray($httpRequest->request->all(), true);

        $response = $this->getFactory()->createOauthToken()->createAccessToken($oauthRequestTransfer);
        $response->headers->add(['Access-Control-Allow-Origin' => '*']);

        return $response;
    }
}

