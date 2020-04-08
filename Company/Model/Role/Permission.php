<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\Role;

use Javid\Company\Model\ResourceModel\Permission\CollectionFactory as PermissionCollectionFactory;

/**
 * Class for managing role permissions.
 */
class Permission
{
    /**
     * @var \Javid\Company\Model\ResourceModel\Permission\CollectionFactory
     */
    private $permissionCollectionFactory;

    /**
     * @var \Magento\Framework\Acl\Data\CacheInterface
     */
    private $aclDataCache;

    /**
     * @var \Javid\Company\Api\AclInterface
     */
    private $userRoleManagement;

    /**
     * @param PermissionCollectionFactory $permissionCollectionFactory
     * @param \Magento\Framework\Acl\Data\CacheInterface $aclDataCache
     * @param \Javid\Company\Api\AclInterface $userRoleManagement
     */
    public function __construct(
        PermissionCollectionFactory $permissionCollectionFactory,
        \Magento\Framework\Acl\Data\CacheInterface $aclDataCache,
        \Javid\Company\Api\AclInterface $userRoleManagement
    ) {
        $this->permissionCollectionFactory = $permissionCollectionFactory;
        $this->aclDataCache = $aclDataCache;
        $this->userRoleManagement = $userRoleManagement;
    }

    /**
     * Gets a number of users assigned to the role.
     *
     * @param int $roleId
     * @return int
     */
    public function getRoleUsersCount($roleId)
    {
        return count($this->userRoleManagement->getUsersByRoleId($roleId));
    }

    /**
     * Get role permissions.
     *
     * @param \Javid\Company\Api\Data\RoleInterface $role
     * @return array
     */
    public function getRolePermissions(\Javid\Company\Api\Data\RoleInterface $role)
    {
        $permissionCollection = $this->permissionCollectionFactory->create();
        $permissionCollection->addFieldToFilter('role_id', ['eq' => $role->getId()])->load();
        return $permissionCollection->getItems();
    }

    /**
     * Delete role permissions.
     *
     * @param \Javid\Company\Api\Data\RoleInterface $role
     * @return void
     */
    public function deleteRolePermissions(\Javid\Company\Api\Data\RoleInterface $role)
    {
        $permissions = $this->getRolePermissions($role);
        foreach ($permissions as $permission) {
            $permission->delete();
        }
        $this->aclDataCache->clean();
    }

    /**
     * Save role permissions.
     *
     * @param \Javid\Company\Api\Data\RoleInterface $role
     * @return void
     */
    public function saveRolePermissions(\Javid\Company\Api\Data\RoleInterface $role)
    {
        $permissions = $role->getPermissions();
        $this->deleteRolePermissions($role);
        foreach ($permissions as $permission) {
            $permission->setRoleId($role->getId());
            $permission->save();
        }
        $this->aclDataCache->clean();
    }
}
