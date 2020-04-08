<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\SaveHandler;

use Javid\Company\Model\SaveHandlerInterface;
use Javid\Company\Api\Data\CompanyInterface;

/**
 * Default role creator.
 */
class DefaultRole implements SaveHandlerInterface
{
    /**
     * @var \Javid\Company\Model\RoleFactory
     */
    private $roleFactory;

    /**
     * @var \Javid\Company\Api\RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * @var \Javid\Company\Model\PermissionManagementInterface
     */
    private $permissionManagement;

    /**
     * @var \Javid\Company\Model\RoleManagement
     */
    private $roleManagement;

    /**
     * @param \Javid\Company\Model\RoleFactory $roleFactory
     * @param \Javid\Company\Api\RoleRepositoryInterface $roleRepository
     * @param \Javid\Company\Model\PermissionManagementInterface $permissionManagement
     * @param \Javid\Company\Model\RoleManagement $roleManagement
     */
    public function __construct(
        \Javid\Company\Model\RoleFactory $roleFactory,
        \Javid\Company\Api\RoleRepositoryInterface $roleRepository,
        \Javid\Company\Model\PermissionManagementInterface $permissionManagement,
        \Javid\Company\Model\RoleManagement $roleManagement
    ) {
        $this->roleFactory = $roleFactory;
        $this->roleRepository = $roleRepository;
        $this->permissionManagement = $permissionManagement;
        $this->roleManagement = $roleManagement;
    }

    /**
     * @inheritdoc
     */
    public function execute(CompanyInterface $company, CompanyInterface $initialCompany)
    {
        if (!$initialCompany->getId()) {
            $role = $this->roleFactory->create();
            $role->setRoleName($this->roleManagement->getCompanyDefaultRoleName());
            $role->setCompanyId($company->getId());
            $role->setPermissions($this->permissionManagement->retrieveDefaultPermissions());
            $this->roleRepository->save($role);
        }
    }
}
