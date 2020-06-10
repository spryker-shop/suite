<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\MerchantUser;

use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery;
use Orm\Zed\User\Persistence\SpyUserQuery;
use Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class MerchantUserWriterStep implements DataImportStepInterface
{
    protected const MERCHANT_KEY = 'merchant_key';
    protected const USERNAME = 'username';

    /**
     * @inheritDoc
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idMerchant = $this->getIdMerchantByKey($dataSet[static::MERCHANT_KEY]);
        $idUser = $this->getIdUserByUsername($dataSet[static::USERNAME]);

        $merchantUserEntity = SpyMerchantUserQuery::create()
            ->filterByFkMerchant($idMerchant)
            ->filterByFkUser($idUser)
            ->findOneOrCreate();

        $merchantUserEntity->save();
    }

    /**
     * @param string $merchantKey
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdMerchantByKey(string $merchantKey): int
    {
        $merchantEntity = SpyMerchantQuery::create()
            ->findOneByMerchantKey($merchantKey);

        if (!$merchantEntity) {
            throw new EntityNotFoundException(sprintf('Merchant with key "%s" is not found.', $merchantKey));
        }

        return $merchantEntity->getIdMerchant();
    }

    /**
     * @param string $username
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdUserByUsername(string $username): int
    {
        $userEntity = SpyUserQuery::create()
            ->findOneByUsername($username);

        if (!$userEntity) {
            throw new EntityNotFoundException(sprintf('User with username "%s" is not found.', $username));
        }

        return $userEntity->getIdUser();
    }
}
