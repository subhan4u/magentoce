<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Plugin\Customer\Api\CustomerRepository;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Javid\Company\Api\Data\CompanyCustomerInterfaceFactory as CompanyCustomerExtension;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotSaveException;
use Javid\Company\Model\Customer\CompanyAttributes;
use Javid\Company\Model\Company\Structure;
use Javid\Company\Api\CompanyRepositoryInterface;

/**
 * A plugin for customer save operation for processing company routines.
 */
class Save
{
    /**
     * @var \Javid\Company\Model\Customer\CompanyAttributes
     */
    private $customerSaveAttributes;

    /**
     * @var \Javid\Company\Api\CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * @param CompanyAttributes $customerSaveAttributes
     * @param CompanyRepositoryInterface $companyRepository
     */
    public function __construct(
        CompanyAttributes $customerSaveAttributes,
        CompanyRepositoryInterface $companyRepository
    ) {
        $this->customerSaveAttributes = $customerSaveAttributes;
        $this->companyRepository = $companyRepository;
    }

    /**
     * Before customer save.
     *
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerInterface $customer
     * @param null $passwordHash [optional]
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(
        CustomerRepositoryInterface $customerRepository,
        CustomerInterface $customer,
        $passwordHash = null
    ) {
        $this->customerSaveAttributes->updateCompanyAttributes($customer);
        $customer = $this->setCustomerGroup($customer);
        return [$customer, $passwordHash];
    }

    /**
     * Set customer group.
     *
     * @param CustomerInterface $customer
     * @return CustomerInterface
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function setCustomerGroup(CustomerInterface $customer)
    {
        $companyId = $this->customerSaveAttributes->getCompanyId();
        if ($companyId) {
            $company = $this->companyRepository->get($companyId);
            $customer->setGroupId($company->getCustomerGroupId());
        }
        return $customer;
    }

    /**
     * After customer save.
     *
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @return CustomerInterface
     * @throws CouldNotSaveException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        CustomerRepositoryInterface $customerRepository,
        CustomerInterface $customer
    ) {
        $this->customerSaveAttributes->saveCompanyAttributes($customer);
        return $customer;
    }
}
