<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Api\Data;

/**
 * Team interface
 *
 * @api
 * @since 100.0.0
 */
interface TeamInterface extends \Magento\Framework\Api\CustomAttributesDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const TEAM_ID       = 'team_id';
    const NAME          = 'name';
    const DESCRIPTION   = 'description';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Javid\Company\Api\Data\TeamInterface
     */
    public function setId($id);

    /**
     * Set name
     *
     * @param string $name
     * @return \Javid\Company\Api\Data\TeamInterface
     */
    public function setName($name);

    /**
     * Set description
     *
     * @param string $description
     * @return \Javid\Company\Api\Data\TeamInterface
     */
    public function setDescription($description);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Javid\Company\Api\Data\TeamExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Javid\Company\Api\Data\TeamExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Javid\Company\Api\Data\TeamExtensionInterface $extensionAttributes);
}
