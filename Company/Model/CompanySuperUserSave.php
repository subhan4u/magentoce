<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model;

/**
 * Process company user save.
 */
class CompanySuperUserSave
{
    /**
     * @var \Javid\Company\Model\Customer\CompanyAttributes
     */
    private $companyAttributes;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    private $customerManager;

    /**
     * @var \Javid\Company\Model\Email\Sender
     */
    private $companyEmailSender;

    /**
     * @var \Javid\Company\Model\Action\Company\ReplaceSuperUser
     */
    private $replaceSuperUser;

    /**
     * @param \Javid\Company\Model\Customer\CompanyAttributes $companyAttributes
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Api\AccountManagementInterface $customerManager
     * @param \Javid\Company\Model\Email\Sender $companyEmailSender
     * @param \Javid\Company\Model\Action\Company\ReplaceSuperUser $replaceSuperUser
     */
    public function __construct(
        \Javid\Company\Model\Customer\CompanyAttributes $companyAttributes,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\AccountManagementInterface $customerManager,
        \Javid\Company\Model\Email\Sender $companyEmailSender,
        \Javid\Company\Model\Action\Company\ReplaceSuperUser $replaceSuperUser
    ) {
        $this->companyAttributes = $companyAttributes;
        $this->customerRepository = $customerRepository;
        $this->customerManager = $customerManager;
        $this->companyEmailSender = $companyEmailSender;
        $this->replaceSuperUser = $replaceSuperUser;
    }

    /**
     * Save customer, send emails.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param \Magento\Customer\Api\Data\CustomerInterface|null $currentSuperUser [optional]
     * @param int|null $companyStatus [optional]
     * @param bool $keepActive [optional]
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     */
    public function saveCustomer(
        \Magento\Customer\Api\Data\CustomerInterface $customer,
        \Magento\Customer\Api\Data\CustomerInterface $currentSuperUser = null,
        $companyStatus = null,
        $keepActive = true
    ) {
        $savedCustomer = $this->saveCustomerAccount($customer);
        $oldSuperUserId = $currentSuperUser ? $currentSuperUser->getId() : null;
        $this->replaceSuperUser->execute($savedCustomer, $oldSuperUserId, $keepActive);
        if ((!$customer->getId() || (int)$customer->getId() != $oldSuperUserId)
            && ($companyStatus === \Javid\Company\Api\Data\CompanyInterface::STATUS_APPROVED)
        ) {
            $companyAttributes = $this->companyAttributes->getCompanyAttributesByCustomer($customer);
            if ($companyAttributes === null) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(
                    __('No such entity with id = %idValue', ['idValue' => $customer->getId()])
                );
            }
            $companyId = $companyAttributes->getCompanyId();
            $this->sendEmails($companyId, $savedCustomer, $oldSuperUserId, $keepActive);
        }
        return $savedCustomer;
    }

    /**
     * Create customer account (if account new) or update existing.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function saveCustomerAccount(\Magento\Customer\Api\Data\CustomerInterface $customer)
    {
        $savedCustomer = $customer->getId()
            ? $this->customerRepository->save($customer)
            : $this->customerManager->createAccount($customer);
        return $savedCustomer;
    }

    /**
     * Send super user assign, unassign, inactivate email notifications.
     *
     * @param int $companyId
     * @param \Magento\Customer\Api\Data\CustomerInterface $savedCustomer
     * @param int $oldSuperUser
     * @param bool $keepActive
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function sendEmails(
        $companyId,
        \Magento\Customer\Api\Data\CustomerInterface $savedCustomer,
        $oldSuperUser,
        $keepActive
    ) {
        if ($companyId !== null) {
            $this->companyEmailSender->sendAssignSuperUserNotificationEmail(
                $savedCustomer,
                $companyId
            );
            if ($oldSuperUser) {
                $oldCustomer = $this->customerRepository->getById($oldSuperUser);
                if ($keepActive) {
                    $this->companyEmailSender->sendRemoveSuperUserNotificationEmail(
                        $oldCustomer,
                        $companyId
                    );
                } else {
                    $this->companyEmailSender->sendInactivateSuperUserNotificationEmail(
                        $oldCustomer,
                        $companyId
                    );
                }
            }
        }
    }
}
