<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model;

/**
 * A repository class for role entity that provides basic CRUD operations.
 */
class RoleRepository implements \Javid\Company\Api\RoleRepositoryInterface
{
    /**
     * @var \Javid\Company\Api\Data\RoleInterface[]
     */
    private $instances = [];

    /**
     * @var \Javid\Company\Api\Data\RoleInterfaceFactory
     */
    private $roleFactory;

    /**
     * @var \Javid\Company\Model\ResourceModel\Role
     */
    private $roleResource;

    /**
     * @var \Javid\Company\Model\ResourceModel\Role\CollectionFactory
     */
    private $roleCollectionFactory;

    /**
     * @var \Javid\Company\Api\Data\RoleSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var \Javid\Company\Model\Role\Permission
     */
    private $rolePermission;

    /**
     * @var \Javid\Company\Model\PermissionManagementInterface
     */
    private $permissionManagement;

    /**
     * @var \Javid\Company\Model\Role\Validator
     */
    private $validator;

    /**
     * @param \Javid\Company\Api\Data\RoleInterfaceFactory $roleFactory
     * @param \Javid\Company\Model\ResourceModel\Role $roleResource
     * @param \Javid\Company\Model\ResourceModel\Role\CollectionFactory $roleCollectionFactory
     * @param \Javid\Company\Api\Data\RoleSearchResultsInterfaceFactory $searchResultsFactory
     * @param \Javid\Company\Model\Role\Permission $rolePermission
     * @param \Javid\Company\Model\PermissionManagementInterface $permissionManagement
     * @param \Javid\Company\Model\Role\Validator $validator
     *
     */
    public function __construct(
        \Javid\Company\Api\Data\RoleInterfaceFactory $roleFactory,
        \Javid\Company\Model\ResourceModel\Role $roleResource,
        \Javid\Company\Model\ResourceModel\Role\CollectionFactory $roleCollectionFactory,
        \Javid\Company\Api\Data\RoleSearchResultsInterfaceFactory $searchResultsFactory,
        \Javid\Company\Model\Role\Permission $rolePermission,
        \Javid\Company\Model\PermissionManagementInterface $permissionManagement,
        \Javid\Company\Model\Role\Validator $validator
    ) {
        $this->roleFactory = $roleFactory;
        $this->roleResource = $roleResource;
        $this->roleCollectionFactory = $roleCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->rolePermission = $rolePermission;
        $this->permissionManagement = $permissionManagement;
        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
    public function save(\Javid\Company\Api\Data\RoleInterface $role)
    {
        $role = $this->validator->retrieveRole($role);
        $allowedResources = $this->permissionManagement->retrieveAllowedResources($role->getPermissions());
        $permissions = $this->permissionManagement->populatePermissions($allowedResources);
        $this->validator->validatePermissions($permissions, $allowedResources);
        $role->setPermissions($permissions);
        if ($this->validateRoleName($role) === false) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__(
                'User role with this name already exists. Enter a different name to save this role.'
            ));
        }
        $this->roleResource->save($role);
        $this->rolePermission->saveRolePermissions($role);
        unset($this->instances[$role->getId()]);

        return $role;
    }

    /**
     * Validate that role name is unique.
     *
     * @param \Javid\Company\Api\Data\RoleInterface $role
     * @return bool
     */
    private function validateRoleName(\Javid\Company\Api\Data\RoleInterface $role)
    {
        $collection = $this->roleCollectionFactory->create();
        $collection->addFieldToFilter(
            \Javid\Company\Api\Data\RoleInterface::ROLE_NAME,
            ['eq' => $role->getRoleName()]
        );
        $collection->addFieldToFilter(
            \Javid\Company\Api\Data\RoleInterface::COMPANY_ID,
            ['eq' => $role->getCompanyId()]
        );

        if ($role->getId()) {
            $collection->addFieldToFilter(
                \Javid\Company\Api\Data\RoleInterface::ROLE_ID,
                ['neq' => $role->getId()]
            );
        }

        return !$collection->getSize();
    }

    /**
     * @inheritdoc
     */
    public function get($roleId)
    {
        if (!isset($this->instances[$roleId])) {
            /** @var \Javid\Company\Api\Data\RoleInterface $role */
            $role = $this->roleFactory->create();
            $this->roleResource->load($role, $roleId);
            $this->validator->checkRoleExist($role, $roleId);
            $role->setPermissions($this->rolePermission->getRolePermissions($role));
            $this->instances[$roleId] = $role;
        }
        return $this->instances[$roleId];
    }

    /**
     * @inheritdoc
     */
    public function delete($roleId)
    {
        $role = $this->get($roleId);
        $this->validator->validateRoleBeforeDelete($role);
        try {
            $this->roleResource->delete($role);
            $this->rolePermission->deleteRolePermissions($role);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __(
                    'Cannot delete role with id %1',
                    $role->getId()
                ),
                $e
            );
        }
        unset($this->instances[$roleId]);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->roleCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? : 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }

        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    $sortOrder->getDirection()
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());

        $items = $collection->getItems();

        foreach ($items as $itemKey => $itemValue) {
            $items[$itemKey]->setPermissions($this->rolePermission->getRolePermissions($itemValue));
        }

        $searchResults->setItems($items);
        return $searchResults;
    }
}
