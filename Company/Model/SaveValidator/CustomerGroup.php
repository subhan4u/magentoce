<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\SaveValidator;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Checks if company customer group exists.
 */
class CustomerGroup implements \Javid\Company\Model\SaveValidatorInterface
{
    /**
     * @var \Javid\Company\Api\Data\CompanyInterface
     */
    private $company;

    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    private $customerGroupRepository;

    /**
     * @param \Javid\Company\Api\Data\CompanyInterface $company
     * @param \Magento\Customer\Api\GroupRepositoryInterface $customerGroupRepository
     */
    public function __construct(
        \Javid\Company\Api\Data\CompanyInterface $company,
        \Magento\Customer\Api\GroupRepositoryInterface $customerGroupRepository
    ) {
        $this->company = $company;
        $this->customerGroupRepository = $customerGroupRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        try {
            $this->customerGroupRepository->getById($this->company->getCustomerGroupId());
        } catch (NoSuchEntityException $e) {
            throw new NoSuchEntityException(
                __(
                    'No such entity with %fieldName = %fieldValue',
                    [
                        'fieldName' => 'customerGroupId',
                        'fieldValue' => $this->company->getCustomerGroupId()
                    ]
                )
            );
        }
    }
}
