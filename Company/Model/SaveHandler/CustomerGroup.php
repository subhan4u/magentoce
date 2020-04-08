<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\SaveHandler;

use Javid\Company\Model\SaveHandlerInterface;
use Javid\Company\Api\Data\CompanyInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Javid\Company\Model\ResourceModel\Customer as CustomerResource;

/**
 * Customer group save handler.
 */
class CustomerGroup implements SaveHandlerInterface
{
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Javid\Company\Model\ResourceModel\Customer
     */
    private $customerResource;

    /**
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerResource $customerResource
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CustomerResource $customerResource
    ) {
        $this->customerResource = $customerResource;
        $this->customerRepository = $customerRepository;
    }

    /**
     * {@inheritdoc}
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(CompanyInterface $company, CompanyInterface $initialCompany)
    {
        if (!$initialCompany->getId() || $initialCompany->getCustomerGroupId() != $company->getCustomerGroupId()) {
            $customerIds = $this->customerResource->getCustomerIdsByCompanyId($company->getId());
            foreach ($customerIds as $customerId) {
                $customer = $this->customerRepository->getById($customerId);
                $customer->setGroupId($company->getCustomerGroupId());
                $this->customerRepository->save($customer);
            }
        }
    }
}
