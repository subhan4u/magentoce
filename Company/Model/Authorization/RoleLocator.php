<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Model\Authorization;

use Magento\Authorization\Model\UserContextInterface;

/**
 * Class RoleLocator.
 */
class RoleLocator implements \Magento\Framework\Authorization\RoleLocatorInterface
{
    /**
     * Role id.
     *
     * @var int
     */
    private $roleId;

    /**
     * @var \Magento\Authorization\Model\UserContextInterface
     */
    private $userContext;

    /**
     * @var \Javid\Company\Api\AclInterface
     */
    private $roleManagement;

    /**
     * @param \Javid\Company\Model\CompanyAdminPermission
     */
    private $adminPermission;

    /**
     * @param \Magento\Authorization\Model\UserContextInterface $userContext
     * @param \Javid\Company\Api\AclInterface $roleManagement
     * @param \Javid\Company\Model\CompanyAdminPermission $adminPermission
     */
    public function __construct(
        \Magento\Authorization\Model\UserContextInterface $userContext,
        \Javid\Company\Api\AclInterface $roleManagement,
        \Javid\Company\Model\CompanyAdminPermission $adminPermission
    ) {
        $this->userContext = $userContext;
        $this->roleManagement = $roleManagement;
        $this->adminPermission = $adminPermission;
    }

    /**
     * Retrieve current role.
     *
     * @return string|null
     */
    public function getAclRoleId()
    {
        $roleId = null;
        $userId = $this->userContext->getUserId();
        $userType = $this->userContext->getUserType();
        if ($userId && !$this->roleId && $userType == UserContextInterface::USER_TYPE_CUSTOMER) {
            $roles = $this->roleManagement->getRolesByUserId($userId);
            if (!empty($roles)) {
                $role = array_shift($roles);
                $roleId = $role->getData('role_id');
            } elseif ($this->adminPermission->isGivenUserCompanyAdmin($userId)) {
                $roleId = 0;
            }
            $this->roleId = $roleId;
        }
        return $this->roleId;
    }
}
