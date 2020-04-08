<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\Role;

/**
 * Validator for Role data.
 */
class Validator
{
    /**
     * @var \Javid\Company\Api\CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * @var \Javid\Company\Api\RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * @var \Javid\Company\Api\AclInterface
     */
    private $userRoleManagement;

    /**
     * @var \Javid\Company\Api\RoleManagementInterface
     */
    private $roleManagement;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param \Javid\Company\Api\CompanyRepositoryInterface $companyRepository
     * @param \Javid\Company\Api\RoleRepositoryInterface $roleRepository
     * @param \Javid\Company\Api\AclInterface $userRoleManagement
     * @param \Javid\Company\Api\RoleManagementInterface $roleManagement
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        \Javid\Company\Api\CompanyRepositoryInterface $companyRepository,
        \Javid\Company\Api\RoleRepositoryInterface $roleRepository,
        \Javid\Company\Api\AclInterface $userRoleManagement,
        \Javid\Company\Api\RoleManagementInterface $roleManagement,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
    ) {
        $this->companyRepository = $companyRepository;
        $this->roleRepository = $roleRepository;
        $this->userRoleManagement = $userRoleManagement;
        $this->roleManagement = $roleManagement;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Merges requested role object onto the original role and validate role data.
     *
     * @param \Javid\Company\Api\Data\RoleInterface $requestedRole
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return \Javid\Company\Api\Data\RoleInterface
     */
    public function retrieveRole(\Javid\Company\Api\Data\RoleInterface $requestedRole)
    {
        if ($requestedRole->getId()) {
            $requestedCompanyId = $requestedRole->getCompanyId();
            $originalRole = $this->roleRepository->get($requestedRole->getId());
            $this->dataObjectHelper->mergeDataObjects(
                \Javid\Company\Api\Data\RoleInterface::class,
                $originalRole,
                $requestedRole
            );
            $role = $originalRole;
            if ($requestedCompanyId && $role->getCompanyId() != $requestedCompanyId) {
                throw new \Magento\Framework\Exception\InputException(
                    __(
                        'Invalid value of "%value" provided for the %fieldName field.',
                        ['fieldName' => 'company_id', 'value' => $requestedCompanyId]
                    )
                );
            }
        } else {
            $role = $requestedRole;
        }
        if (!$role->getRoleName()) {
            throw new \Magento\Framework\Exception\InputException(
                __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'role_name'])
            );
        }
        if (!$role->getId() && !$role->getCompanyId()) {
            throw new \Magento\Framework\Exception\InputException(
                __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'company_id'])
            );
        }
        try {
            $this->companyRepository->get($role->getCompanyId());
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __(
                    'No such entity with %fieldName = %fieldValue',
                    ['fieldName' => 'company_id', 'fieldValue' => $role->getCompanyId()]
                )
            );
        }

        return $role;
    }

    /**
     * Validate permissions before saving the role.
     *
     * @param \Javid\Company\Api\Data\PermissionInterface[] $permissions
     * @param array $allowedResources
     * @throws \Magento\Framework\Exception\InputException
     * @return void
     */
    public function validatePermissions(array $permissions, array $allowedResources)
    {
        $allResources = [];
        foreach ($permissions as $permission) {
            $allResources[] = $permission->getResourceId();
        }
        $invalidResources = array_diff($allowedResources, $allResources);
        if ($invalidResources) {
            throw new \Magento\Framework\Exception\InputException(
                __(
                    'Invalid value of "%value" provided for the %fieldName field.',
                    ['fieldName' => 'resource_id', 'value' => $invalidResources[0]]
                )
            );
        }
    }

    /**
     * Validates the role before delete.
     *
     * @param \Javid\Company\Api\Data\RoleInterface $role
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @return void
     */
    public function validateRoleBeforeDelete(\Javid\Company\Api\Data\RoleInterface $role)
    {
        $roleUsers = $this->userRoleManagement->getUsersCountByRoleId($role->getId());
        if ($roleUsers) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __(
                    'This role cannot be deleted because users are assigned to it. '
                    . 'Reassign the users to another role to continue.'
                )
            );
        }
        $roles = $this->roleManagement->getRolesByCompanyId($role->getCompanyId(), false);
        if (count($roles) <= 1) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __(
                    'You cannot delete a role when it is the only role in the company. '
                    . 'You must create another role before deleting this role.'
                )
            );
        }
    }

    /**
     * Check if Role exist.
     *
     * @param \Javid\Company\Api\Data\RoleInterface $role
     * @param int $roleId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return void
     */
    public function checkRoleExist(
        \Javid\Company\Api\Data\RoleInterface $role,
        $roleId
    ) {
        if (!$role->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('No such entity with %fieldName = %fieldValue', ['fieldName' => 'roleId', 'fieldValue' => $roleId])
            );
        }
    }
}
