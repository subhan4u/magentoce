<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Api;

/**
 * Interface for managing company roles.
 *
 * @api
 * @since 100.0.0
 */
interface RoleManagementInterface
{
    /**
     * Get roles by company id.
     *
     * @param int $companyId
     * @param bool $includeAdminRole [optional]
     * @return \Javid\Company\Api\Data\RoleInterface[]
     */
    public function getRolesByCompanyId($companyId, $includeAdminRole = true);

    /**
     * Get admin role.
     *
     * @return \Javid\Company\Api\Data\RoleInterface
     */
    public function getAdminRole();

    /**
     * Get company default role.
     *
     * @param int $companyId
     * @return \Javid\Company\Api\Data\RoleInterface
     */
    public function getCompanyDefaultRole($companyId);
}
