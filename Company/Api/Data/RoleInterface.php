<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Api\Data;

/**
 * Role data transfer object interface.
 *
 * @api
 * @since 100.0.0
 */
interface RoleInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ROLE_ID = 'role_id';
    const SORT_ORDER = 'sort_order';
    const ROLE_NAME = 'role_name';
    const COMPANY_ID = 'company_id';
    /**#@-*/

    /**
     * Set id.
     *
     * @param int $id
     * @return \Javid\Company\Api\Data\RoleInterface
     */
    public function setId($id);

    /**
     * Set role name.
     *
     * @param string $name
     * @return \Javid\Company\Api\Data\RoleInterface
     */
    public function setRoleName($name);

    /**
     * Set company id.
     *
     * @param int $id
     * @return \Javid\Company\Api\Data\RoleInterface
     */
    public function setCompanyId($id);

    /**
     * Set permissions.
     *
     * @param \Javid\Company\Api\Data\PermissionInterface[] $permissions
     * @return \Javid\Company\Api\Data\RoleInterface
     */
    public function setPermissions(array $permissions);

    /**
     * Set extension attributes.
     *
     * @param \Javid\Company\Api\Data\RoleExtensionInterface $extensionAttribute
     * @return \Javid\Company\Api\Data\RoleInterface
     */
    public function setExtensionAttributes(\Javid\Company\Api\Data\RoleExtensionInterface $extensionAttribute);

    /**
     * Get role id.
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get role name.
     *
     * @return string|null
     */
    public function getRoleName();

    /**
     * Get permissions.
     *
     * @return \Javid\Company\Api\Data\PermissionInterface[]
     */
    public function getPermissions();

    /**
     * Get company id.
     *
     * @return int|null
     */
    public function getCompanyId();

    /**
     * Get extension attributes.
     *
     * @return \Javid\Company\Api\Data\RoleExtensionInterface|null
     */
    public function getExtensionAttributes();
}
