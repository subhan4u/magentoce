<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model;

use Javid\Company\Api\Data\TeamInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class Team extends AbstractExtensibleModel implements TeamInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'team';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'team';

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = TeamInterface::TEAM_ID;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Javid\Company\Model\ResourceModel\Team::class);
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::TEAM_ID);
    }

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \Javid\Company\Api\Data\TeamInterface
     */
    public function setId($id)
    {
        return $this->setData(self::TEAM_ID, $id);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return \Javid\Company\Api\Data\TeamInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set description
     *
     * @param string $description
     * @return \Javid\Company\Api\Data\TeamInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Javid\Company\Api\Data\TeamExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object
     *
     * @param \Javid\Company\Api\Data\TeamExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Javid\Company\Api\Data\TeamExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
