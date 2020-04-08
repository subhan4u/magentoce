<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Model\Authorization\Loader;

/**
 * Access Control List loader.
 */
class Role implements \Magento\Framework\Acl\LoaderInterface
{
    /**
     * @var \Javid\Company\Model\ResourceModel\Role\Collection
     */
    private $collection;

    /**
     * @var \Magento\Authorization\Model\Acl\Role\UserFactory
     */
    private $roleFactory;

    /**
     * @var \Javid\Company\Model\CompanyUser
     */
    private $companyUser;

    /**
     * @param \Magento\Authorization\Model\Acl\Role\UserFactory $roleFactory
     * @param \Javid\Company\Model\ResourceModel\Role\Collection $collection
     * @param \Javid\Company\Model\CompanyUser $companyUser
     */
    public function __construct(
        \Magento\Authorization\Model\Acl\Role\UserFactory $roleFactory,
        \Javid\Company\Model\ResourceModel\Role\Collection $collection,
        \Javid\Company\Model\CompanyUser $companyUser
    ) {
        $this->roleFactory = $roleFactory;
        $this->collection = $collection;
        $this->companyUser = $companyUser;
    }

    /**
     * Populate ACL with roles from external storage.
     *
     * @param \Magento\Framework\Acl $acl
     * @return void
     */
    public function populateAcl(\Magento\Framework\Acl $acl)
    {
        $companyId = $this->companyUser->getCurrentCompanyId();
        if ($companyId) {
            $this->collection->addFieldToFilter(\Javid\Company\Api\Data\RoleInterface::COMPANY_ID, $companyId);
            $roles = $this->collection->getItems();
            /** @var \Javid\Company\Api\Data\RoleInterface $role */
            foreach ($roles as $role) {
                $acl->addRole($this->roleFactory->create(['roleId' => $role->getId()]));
            }
        }
    }
}
