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
use Magento\Framework\Exception\CouldNotDeleteException;
use Javid\Company\Model\Customer\CompanyAttributes;
use Javid\Company\Model\Company\Structure;
use Javid\Company\Api\CompanyRepositoryInterface;

/**
 * A plugin for customer delete operation for processing company routines.
 */
class Delete
{
    /**
     * @var \Javid\Company\Api\Data\CompanyCustomerInterfaceFactory
     */
    private $companyCustomerAttributes;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var \Javid\Company\Model\Customer\CompanyAttributes
     */
    private $customerSaveAttributes;

    /**
     * @var \Javid\Company\Model\Company\Structure
     */
    private $companyStructure;

    /**
     * @var \Javid\Company\Api\CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * @param CompanyCustomerExtension $companyCustomerAttributes
     * @param DataObjectHelper $dataObjectHelper
     * @param CompanyAttributes $customerSaveAttributes
     * @param Structure $companyStructure
     * @param CompanyRepositoryInterface $companyRepository
     */
    public function __construct(
        CompanyCustomerExtension $companyCustomerAttributes,
        DataObjectHelper $dataObjectHelper,
        CompanyAttributes $customerSaveAttributes,
        Structure $companyStructure,
        CompanyRepositoryInterface $companyRepository
    ) {
        $this->companyCustomerAttributes = $companyCustomerAttributes;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->customerSaveAttributes = $customerSaveAttributes;
        $this->companyStructure = $companyStructure;
        $this->companyRepository = $companyRepository;
    }

    /**
     * Around delete.
     *
     * @param CustomerRepositoryInterface $subject
     * @param \Closure $proceed
     * @param CustomerInterface $customer
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundDelete(
        CustomerRepositoryInterface $subject,
        \Closure $proceed,
        CustomerInterface $customer
    ) {
        $this->checkIsSuperUser($customer);
        $deleteResult = $proceed($customer);
        $this->rebuildCompanyStructure($customer);

        return $deleteResult;
    }

    /**
     * Around delete by customer id.
     *
     * @param CustomerRepositoryInterface $subject
     * @param \Closure $proceed
     * @param int $customerId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws CouldNotDeleteException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundDeleteById(
        CustomerRepositoryInterface $subject,
        \Closure $proceed,
        $customerId
    ) {
        $customer = $subject->getById($customerId);
        $this->checkIsSuperUser($customer);
        $deleteResult = $proceed($customerId);
        $this->rebuildCompanyStructure($customer);

        return $deleteResult;
    }

    /**
     * Rebuild company structure.
     *
     * @param CustomerInterface $customer
     * @return void
     */
    private function rebuildCompanyStructure(CustomerInterface $customer)
    {
        $this->companyStructure->moveStructureChildrenToParent($customer->getId());
        $this->companyStructure->removeCustomerNode($customer->getId());
    }

    /**
     * Checks if customer is super user of a company.
     *
     * @param CustomerInterface $customer
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    private function checkIsSuperUser(CustomerInterface $customer)
    {
        $companyAttributes = $this->getCompanyAttributes($customer);
        if ($companyAttributes && $companyAttributes->getCompanyId()) {
            $company = $this->companyRepository->get($companyAttributes->getCompanyId());
            if ($company->getSuperUserId() == $customer->getId()) {
                throw new \Magento\Framework\Exception\CouldNotDeleteException(
                    __(
                        'Cannot delete the company admin. Delete operation has been stopped. '
                        . 'Please repeat the action for the other customers.'
                    )
                );
            }
        }
    }

    /**
     * Get company attributes.
     *
     * @param CustomerInterface $customer
     * @return \Javid\Company\Api\Data\CompanyCustomerInterface
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    private function getCompanyAttributes(CustomerInterface $customer)
    {
        try {
            $companyAttributesArray = $this->customerSaveAttributes->getCompanyAttributes($customer);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __('Something went wrong')
            );
        }
        if (!$companyAttributesArray) {
            return null;
        }
        $companyAttributes = $this->companyCustomerAttributes->create();
        $this->dataObjectHelper->populateWithArray(
            $companyAttributes,
            $companyAttributesArray,
            \Javid\Company\Api\Data\CompanyCustomerInterface::class
        );
        return $companyAttributes;
    }
}
