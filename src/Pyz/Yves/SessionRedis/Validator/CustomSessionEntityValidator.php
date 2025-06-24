<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\SessionRedis\Validator;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;
use Spryker\Shared\SessionRedis\Hasher\HasherInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;
use Spryker\Shared\SessionRedis\Validator\SessionEntityValidator as SprykerSessionEntityValidator;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;

class CustomSessionEntityValidator extends SprykerSessionEntityValidator
{
    /**
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SessionEntityResponseTransfer
     */
    /**
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SessionEntityResponseTransfer
     */
    public function validate(SessionEntityRequestTransfer $sessionEntityRequestTransfer): SessionEntityResponseTransfer
    {
        $forceLogoutKey = 'force_logout_customers';
        $forceLogoutData = $this->redisClient->get($forceLogoutKey);

        if ($forceLogoutData) {
            $usersToLogout = json_decode($forceLogoutData, true);

            if (is_array($usersToLogout) && in_array($sessionEntityRequestTransfer->getIdEntity(), $usersToLogout)) {
                $updatedUsers = array_diff($usersToLogout, [$sessionEntityRequestTransfer->getIdEntity()]);
                $this->redisClient->set($forceLogoutKey, json_encode($updatedUsers));

                return (new SessionEntityResponseTransfer())->setIsSuccessfull(false);
            }
        }

        // Call parent validation
        return parent::validate($sessionEntityRequestTransfer);
    }
}
