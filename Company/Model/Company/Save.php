<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\Company;

use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Class responsible for creating and updating company entities.
 */
class Save
{
    /**
     * @var \Javid\Company\Model\SaveHandlerPool
     */
    private $saveHandlerPool;

    /**
     * @var \Javid\Company\Model\ResourceModel\Company
     */
    private $companyResource;

    /**
     * @var \Javid\Company\Api\Data\CompanyInterfaceFactory
     */
    private $companyFactory;

    /**
     * @var \Javid\Company\Model\SaveValidatorPool
     */
    private $saveValidatorPool;

    /**
     * @var \Magento\User\Model\ResourceModel\User\CollectionFactory
     */
    private $userCollectionFactory;

    /**
     * @param \Javid\Company\Model\SaveHandlerPool $saveHandlerPool
     * @param \Javid\Company\Model\ResourceModel\Company $companyResource
     * @param \Javid\Company\Api\Data\CompanyInterfaceFactory $companyFactory
     * @param \Javid\Company\Model\SaveValidatorPool $saveValidatorPool
     * @param \Magento\User\Model\ResourceModel\User\CollectionFactory $userCollectionFactory
     */
    public function __construct(
        \Javid\Company\Model\SaveHandlerPool $saveHandlerPool,
        \Javid\Company\Model\ResourceModel\Company $companyResource,
        \Javid\Company\Api\Data\CompanyInterfaceFactory $companyFactory,
        \Javid\Company\Model\SaveValidatorPool $saveValidatorPool,
        \Magento\User\Model\ResourceModel\User\CollectionFactory $userCollectionFactory
    ) {
        $this->saveHandlerPool = $saveHandlerPool;
        $this->companyResource = $companyResource;
        $this->companyFactory = $companyFactory;
        $this->saveValidatorPool = $saveValidatorPool;
        $this->userCollectionFactory = $userCollectionFactory;
    }

    /**
     * Checks if provided data for a company is correct, saves the company entity and executes additional save handlers
     * from the pool.
     *
     * @param \Javid\Company\Api\Data\CompanyInterface $company
     * @return \Javid\Company\Api\Data\CompanyInterface
     * @throws CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Javid\Company\Api\Data\CompanyInterface $company)
    {
        $this->processAddress($company);
        $this->processSalesRepresentative($company);
        $companyId = $company->getId();
        $initialCompany = $this->getInitialCompany($companyId);
        $this->saveValidatorPool->execute($company, $initialCompany);
        try {
            $this->companyResource->save($company);
            $this->saveHandlerPool->execute($company, $initialCompany);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('Could not save company'),
                $e
            );
        }

        return $company;
    }

    /**
     * Get initial company.
     *
     * @param int|null $companyId
     * @return \Javid\Company\Api\Data\CompanyInterface
     */
    private function getInitialCompany($companyId)
    {
        $company = $this->companyFactory->create();
        try {
            $this->companyResource->load($company, $companyId);
        } catch (\Exception $e) {
            //Do nothing, just leave the object blank.
        }

        return $company;
    }

    /**
     * Set default sales representative (admin user responsible for company) if it is not set.
     *
     * @param \Javid\Company\Api\Data\CompanyInterface $company
     * @return void
     */
    private function processSalesRepresentative(\Javid\Company\Api\Data\CompanyInterface $company)
    {
        if (!$company->getSalesRepresentativeId()) {
            /** @var \Magento\User\Model\ResourceModel\User\Collection $userCollection */
            $userCollection = $this->userCollectionFactory->create();
            $company->setSalesRepresentativeId($userCollection->setPageSize(1)->getFirstItem()->getId());
        }
    }

    /**
     * Prepare company address.
     *
     * @param \Javid\Company\Api\Data\CompanyInterface $company
     * @return void
     */
    private function processAddress(\Javid\Company\Api\Data\CompanyInterface $company)
    {
        if (!$company->getRegionId()) {
            $company->setRegionId(null);
        } else {
            $company->setRegion(null);
        }
        $street = $company->getStreet();
        if (is_array($street) && count($street)) {
            $company->setStreet(trim(implode("\n", $street)));
        }
    }
}
