<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Customer\RestApi;

use Codeception\Example;
use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomerRestorePasswordAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use PyzTest\Glue\Customer\CustomerApiTester;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Customer
 * @group RestApi
 * @group CustomerRestorePasswordCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CustomerRestorePasswordCest
{
    /**
     * @var \PyzTest\Glue\Customer\RestApi\CustomerRestApiFixtures
     */
    protected $fixtures;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @param \PyzTest\Glue\Customer\CustomerApiTester $I
     *
     * @return void
     */
    public function _before(CustomerApiTester $I): void
    {
        /** @var \PyzTest\Glue\Customer\RestApi\CustomerRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(CustomerRestApiFixtures::class);

        $this->fixtures = $fixtures;

        $this->customerTransfer = $I->haveCustomer(
            [
                CustomerTransfer::NEW_PASSWORD => 'change123',
                CustomerTransfer::PASSWORD => 'change123',
                CustomerTransfer::RESTORE_PASSWORD_KEY => uniqid(),
            ]
        );
        $I->confirmCustomer($this->customerTransfer);
    }

    /**
     * @param \PyzTest\Glue\Customer\CustomerApiTester $I
     *
     * @return void
     */
    public function requestPatchCustomerRestorePasswordUpdatesCustomerPassword(CustomerApiTester $I): void
    {
        $I->markTestSkipped('The positive test is skipped till a Glue test bootstrap is introduced.');

        $restCustomerRestorePasswordAttributesTransfer = (new RestCustomerRestorePasswordAttributesTransfer())
            ->setPassword('qwertyuI1!')
            ->setConfirmPassword('qwertyuI1!')
            ->setRestorePasswordKey($this->customerTransfer->getRestorePasswordKey());

        $I->sendPatch(
            $I->formatUrl(
                '{resourceCustomerPasswordRestore}/1',
                [
                    'resourceCustomerPasswordRestore' => CustomersRestApiConfig::RESOURCE_CUSTOMER_RESTORE_PASSWORD,
                ]
            ),
            [
                'data' => [
                    'type' => CustomersRestApiConfig::RESOURCE_CUSTOMER_RESTORE_PASSWORD,
                    'id' => '1',
                    'attributes' => $restCustomerRestorePasswordAttributesTransfer->modifiedToArray(true, true),
                ],
            ]
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    /**
     * @dataProvider requestPatchCustomerPasswordFailsValidationDataProvider
     *
     * @param \PyzTest\Glue\Customer\CustomerApiTester $I
     * @param \Codeception\Example $example
     *
     * @return void
     */
    public function requestPatchCustomerPasswordFailsValidation(CustomerApiTester $I, Example $example): void
    {
        if ($example['skip'] === true) {
            $I->markTestSkipped('This validation does not work for now.');
        }

        $attributes = array_merge(
            $example['attributes'],
            [
                RestCustomerRestorePasswordAttributesTransfer::RESTORE_PASSWORD_KEY => $this->customerTransfer->getRestorePasswordKey(),
            ]
        );

        $I->sendPatch(
            $I->formatUrl(
                '{resourceCustomerPasswordRestore}/1',
                [
                    'resourceCustomerPasswordRestore' => CustomersRestApiConfig::RESOURCE_CUSTOMER_RESTORE_PASSWORD,
                ]
            ),
            [
                'data' => [
                    'type' => CustomersRestApiConfig::RESOURCE_CUSTOMER_RESTORE_PASSWORD,
                    'id' => '1',
                    'attributes' => $attributes,
                ],
            ]
        );

        // Assert
        $I->seeResponseCodeIs($example[RestErrorMessageTransfer::STATUS]);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        foreach ($example['errors'] as $index => $error) {
            $I->seeResponseErrorsHaveCode($error[RestErrorMessageTransfer::CODE], $index);
            $I->seeResponseErrorsHaveStatus($error[RestErrorMessageTransfer::STATUS], $index);
            $I->seeResponseErrorsHaveDetail($error[RestErrorMessageTransfer::DETAIL], $index);
        }
    }

    /**
     * @return array
     */
    protected function requestPatchCustomerPasswordFailsValidationDataProvider(): array
    {
        return [
            [
                'attributes' => [
                    RestCustomerRestorePasswordAttributesTransfer::PASSWORD => 'qwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiop',
                    RestCustomerRestorePasswordAttributesTransfer::CONFIRM_PASSWORD => 'qwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiop',
                ],
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => [
                    [
                        RestErrorMessageTransfer::CODE => RestRequestValidatorConfig::RESPONSE_CODE_REQUEST_INVALID,
                        RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                        RestErrorMessageTransfer::DETAIL => 'password => This value is too long. It should have 64 characters or less.',
                    ],
                    [
                        RestErrorMessageTransfer::CODE => RestRequestValidatorConfig::RESPONSE_CODE_REQUEST_INVALID,
                        RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                        RestErrorMessageTransfer::DETAIL => 'confirmPassword => This value is too long. It should have 64 characters or less.',
                    ],
                ],
                'skip' => false,
            ],
            [
                'attributes' => [
                    RestCustomerRestorePasswordAttributesTransfer::PASSWORD => 'qwe',
                    RestCustomerRestorePasswordAttributesTransfer::CONFIRM_PASSWORD => 'qwe',
                ],
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => [
                    [
                        RestErrorMessageTransfer::CODE => RestRequestValidatorConfig::RESPONSE_CODE_REQUEST_INVALID,
                        RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                        RestErrorMessageTransfer::DETAIL => 'password => This value is too short. It should have 8 characters or more.',
                    ],
                    [
                        RestErrorMessageTransfer::CODE => RestRequestValidatorConfig::RESPONSE_CODE_REQUEST_INVALID,
                        RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                        RestErrorMessageTransfer::DETAIL => 'confirmPassword => This value is too short. It should have 8 characters or more.',
                    ],
                ],
                'skip' => false,
            ],
            [
                'attributes' => [
                    RestCustomerRestorePasswordAttributesTransfer::PASSWORD => 'qwertyui',
                    RestCustomerRestorePasswordAttributesTransfer::CONFIRM_PASSWORD => 'qwertyui',
                ],
                RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    [
                        RestErrorMessageTransfer::CODE => CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_PASSWORD_INVALID_CHARACTER_SET,
                        RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                        RestErrorMessageTransfer::DETAIL => CustomersRestApiConfig::RESPONSE_MESSAGE_CUSTOMER_PASSWORD_INVALID_CHARACTER_SET,
                    ],
                ],
                'skip' => true,
            ],
            [
                'attributes' => [
                    RestCustomerRestorePasswordAttributesTransfer::PASSWORD => 'qwertyuI1!eee',
                    RestCustomerRestorePasswordAttributesTransfer::CONFIRM_PASSWORD => 'qwertyuI1!eee',
                ],
                RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                'errors' => [
                    [
                        RestErrorMessageTransfer::CODE => CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_PASSWORD_SEQUENCE_NOT_ALLOWED,
                        RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                        RestErrorMessageTransfer::DETAIL => CustomersRestApiConfig::RESPONSE_MESSAGE_CUSTOMER_PASSWORD_SEQUENCE_NOT_ALLOWED,
                    ],
                ],
                'skip' => true,
            ],
        ];
    }
}
