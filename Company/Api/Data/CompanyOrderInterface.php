<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Api\Data;

/**
 * Order company extension attributes interface. Adds new company attributes to orders.
 *
 * @api
 * @since 100.0.0
 */
interface CompanyOrderInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const ORDER_ID = 'order_id';
    const COMPANY_ID = 'company_id';
    const COMPANY_NAME = 'company_name';
    /**#@-*/

    /**
     * Get order ID.
     *
     * @return int|null
     */
    public function getOrderId();

    /**
     * Get company ID.
     *
     * @return int|null
     */
    public function getCompanyId();

    /**
     * Get company name.
     *
     * @return string|null
     */
    public function getCompanyName();

    /**
     * Set order ID.
     *
     * @param int $id
     * @return \Javid\Company\Api\Data\CompanyOrderInterface
     */
    public function setOrderId($id);

    /**
     * Set company ID.
     *
     * @param int $companyId
     * @return \Javid\Company\Api\Data\CompanyOrderInterface
     */
    public function setCompanyId($companyId);

    /**
     * Set company name.
     *
     * @param string $companyName
     * @return \Javid\Company\Api\Data\CompanyOrderInterface
     */
    public function setCompanyName($companyName);

    /**
     * Retrieve existing extension attributes object.
     *
     * @return \Javid\Company\Api\Data\CompanyOrderExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Javid\Company\Api\Data\CompanyOrderExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Javid\Company\Api\Data\CompanyOrderExtensionInterface $extensionAttributes
    );
}
