<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\Customer;

use Magento\Customer\Api\Data\CustomerInterface;
use Javid\Company\Api\Data\CompanyCustomerInterface;
use Javid\Company\Model\ResourceModel\Customer;
use Magento\Customer\Api\GroupManagementInterface;

/**
 * Class for creating new company for customer.
 */
class Company
{
    /**
     * @var \Javid\Company\Api\Data\CompanyInterfaceFactory
     */
    private $companyFactory;

    /**
     * @var \Javid\Company\Api\CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * @var \Javid\Company\Model\Company\Structure
     */
    private $companyStructure;

    /**
     * @var \Javid\Company\Api\Data\CompanyCustomerInterface
     */
    private $customerAttributes;

    /**
     * @var \Javid\Company\Model\ResourceModel\Customer
     */
    private $customerResource;

    /**
     * @var \Magento\Customer\Api\GroupManagementInterface
     */
    private $groupManagement;

    /**
     * @param \Javid\Company\Api\Data\CompanyInterfaceFactory $companyFactory
     * @param \Javid\Company\Api\CompanyRepositoryInterface $companyRepository
     * @param \Javid\Company\Model\Company\Structure $companyStructure
     * @param CompanyCustomerInterface $customerAttributes
     * @param Customer $customerResource
     * @param GroupManagementInterface $groupManagement
     */
    public function __construct(
        \Javid\Company\Api\Data\CompanyInterfaceFactory $companyFactory,
        \Javid\Company\Api\CompanyRepositoryInterface $companyRepository,
        \Javid\Company\Model\Company\Structure $companyStructure,
        CompanyCustomerInterface $customerAttributes,
        Customer $customerResource,
        GroupManagementInterface $groupManagement
    ) {
        $this->companyFactory = $companyFactory;
        $this->companyRepository = $companyRepository;
        $this->companyStructure = $companyStructure;
        $this->customerAttributes = $customerAttributes;
        $this->customerResource = $customerResource;
        $this->groupManagement = $groupManagement;
    }

    /**
     * Create company.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param array $companyData
     * @param string $jobTitle [optional]
     * @return \Javid\Company\Api\Data\CompanyInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function createCompany(CustomerInterface $customer, array $companyData, $jobTitle = null)
    {
        $companyDataObject = $this->companyFactory->create(['data' => $companyData]);
        if ($companyDataObject->getCustomerGroupId() === null) {
            $companyDataObject->setCustomerGroupId($this->groupManagement->getDefaultGroup()->getId());
        }
        $companyDataObject->setSuperUserId($customer->getId());
        $this->companyRepository->save($companyDataObject);

        $this->customerAttributes
            ->setCompanyId($companyDataObject->getId())
            ->setCustomerId($customer->getId());
        if ($jobTitle) {
            $this->customerAttributes->setJobTitle($jobTitle);
        }
        $this->customerResource->saveAdvancedCustomAttributes($this->customerAttributes);

        return $companyDataObject;
    }
}
