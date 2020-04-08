<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Plugin\Customer\Api;

use \Magento\Customer\Api\GroupRepositoryInterface;
use \Javid\Company\Api\Data\CompanyInterface;

/**
 * Reassign all companies from deleted customer group to the default group.
 */
class ReassignCompaniesToDefaultGroup
{
    /**
     * @var \Javid\Company\Api\CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Customer\Api\GroupManagementInterface
     */
    private $groupManagement;

    /**
     * @var \Javid\Company\Api\CompanyManagementInterface
     */
    private $companyManagement;

    /**
     * @param \Javid\Company\Api\CompanyRepositoryInterface $companyRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Customer\Api\GroupManagementInterface $groupManagement
     * @param \Javid\Company\Api\CompanyManagementInterface $companyManagement
     */
    public function __construct(
        \Javid\Company\Api\CompanyRepositoryInterface $companyRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Customer\Api\GroupManagementInterface $groupManagement,
        \Javid\Company\Api\CompanyManagementInterface $companyManagement
    ) {
        $this->companyRepository = $companyRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->groupManagement = $groupManagement;
        $this->companyManagement = $companyManagement;
    }

    /**
     * Around customer group delete.
     *
     * @param GroupRepositoryInterface $subject
     * @param \Closure $method
     * @param int $groupId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundDeleteById(
        GroupRepositoryInterface $subject,
        \Closure $method,
        $groupId
    ) {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(CompanyInterface::CUSTOMER_GROUP_ID, $groupId)
            ->create();
        $companies = $this->companyRepository->getList($searchCriteria)->getItems();
        $result = $method($groupId);
        if ($result) {
            foreach ($companies as $company) {
                $companyAdmin = $this->companyManagement->getAdminByCompanyId($company->getId());
                $defaultGroupId = $this->groupManagement->getDefaultGroup($companyAdmin->getStoreId())->getId();
                $company->setCustomerGroupId($defaultGroupId);
                $this->companyRepository->save($company);
            }
        }
        return $result;
    }
}