<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\MerchantUser;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\User\Persistence\SpyUserQuery;
use Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface;

class MerchantUserWriterStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @param \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(MerchantUserFacadeInterface $merchantUserFacade)
    {
        $this->merchantUserFacade = $merchantUserFacade;
    }

    protected const MERCHANT_REFERENCE = 'merchant_reference';
    protected const USERNAME = 'username';

    /**
     * @inheritDoc
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idMerchant = $this->getIdMerchantByReference($dataSet[static::MERCHANT_REFERENCE]);
        $idUser = $this->getIdUserByUsername($dataSet[static::USERNAME]);

        $merchantUserTransfer = $this->merchantUserFacade->findMerchantUser(
            (new MerchantUserCriteriaTransfer())
                ->setIdUser($idUser)
                ->setIdMerchant($idMerchant)
        );

        if (!$merchantUserTransfer) {
            $userTransfer = $this->merchantUserFacade->findUser(
                (new UserCriteriaTransfer())->setIdUser($idUser)
            );

            $this->merchantUserFacade->createMerchantUser(
                (new MerchantUserTransfer())
                    ->setIdMerchant($idMerchant)
                    ->setUser($userTransfer)
            );
        }
    }

    /**
     * @param string $merchantReference
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdMerchantByReference(string $merchantReference): int
    {
        $merchantEntity = SpyMerchantQuery::create()
            ->findOneByMerchantReference($merchantReference);

        if (!$merchantEntity) {
            throw new EntityNotFoundException(sprintf('Merchant with reference "%s" is not found.', $merchantReference));
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
