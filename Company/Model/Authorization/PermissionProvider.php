<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Model\Authorization;

/**
 * Class PermissionProvider.
 */
class PermissionProvider
{
    /**
     * \Javid\Company\Model\ResourceModel\Permission\Collection
     */
    private $permissionCollection;

    /**
     * @var \Javid\Company\Model\ResourcePool
     */
    private $resourcePool;

    /**
     * PermissionProvider constructor.
     *
     * @param \Javid\Company\Model\ResourceModel\Permission\Collection $permissionCollection
     * @param \Javid\Company\Model\ResourcePool $resourcePool
     */
    public function __construct(
        \Javid\Company\Model\ResourceModel\Permission\Collection $permissionCollection,
        \Javid\Company\Model\ResourcePool $resourcePool
    ) {
        $this->permissionCollection = $permissionCollection;
        $this->resourcePool = $resourcePool;
    }

    /**
     * Retrieve permissions hash array.
     *
     * @param int $roleId
     * @return array
     */
    public function retrieveRolePermissions($roleId)
    {
        return $this->permissionCollection
            ->addFieldToFilter('role_id', ['eq' => $roleId])
            ->toOptionHash('resource_id', 'permission');
    }

    /**
     * Retrieve default role permissions.
     *
     * @return array
     */
    public function retrieveDefaultPermissions()
    {
        $permissions = [];
        $resources = $this->resourcePool->getDefaultResources();
        foreach ($resources as $resource) {
            $permissions[$resource] = 'allow';
        }

        return $permissions;
    }
}
