<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Model;

/**
 * PermissionManagement interface.
 *
 * @api
 * @since 100.0.0
 */
interface PermissionManagementInterface
{
    /**
     * Retrieve allowed resources.
     *
     * @param \Javid\Company\Api\Data\PermissionInterface[] $permissions
     * @return array
     */
    public function retrieveAllowedResources(array $permissions);

    /**
     * Retrieve default role permissions.
     *
     * @return \Javid\Company\Api\Data\PermissionInterface[] $permissions
     */
    public function retrieveDefaultPermissions();

    /**
     * Populate permissions.
     *
     * @param array $allowedResources
     * @return \Javid\Company\Api\Data\PermissionInterface[] $permissions
     */
    public function populatePermissions(array $allowedResources);
}
