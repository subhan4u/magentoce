<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Model\Action\Customer;

use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Class for assigning a role to customer.
 */
class Assign
{
    /**
     * @var \Javid\Company\Api\AclInterface
     */
    private $acl;

    /**
     * @var \Javid\Company\Api\RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * @param \Javid\Company\Api\AclInterface $acl
     * @param \Javid\Company\Api\RoleRepositoryInterface $roleRepository
     */
    public function __construct(
        \Javid\Company\Api\AclInterface $acl,
        \Javid\Company\Api\RoleRepositoryInterface $roleRepository
    ) {
        $this->acl = $acl;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Assign role to customer.
     *
     * @param CustomerInterface $customer
     * @param int $roleId
     * @return CustomerInterface
     */
    public function assignCustomerRole(CustomerInterface $customer, $roleId)
    {
        $role = $this->roleRepository->get($roleId);
        $companyId = $customer->getExtensionAttributes()->getCompanyAttributes()->getCompanyId();

        if ($role && $role->getCompanyId() == $companyId) {
            $this->acl->assignRoles($customer->getId(), [$role]);
        }

        return $customer;
    }
}
