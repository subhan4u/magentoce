<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\ResourceModel\Order;

/**
 * Class CollectionFactory.
 */
class CollectionFactory implements \Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface
{
    /**
     * Object Manager instance.
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager = null;

    /**
     * Instance name to create.
     *
     * @var string
     */
    private $instanceName = \Magento\Sales\Model\ResourceModel\Order\Collection::class;

    /**
     * @var \Javid\Company\Model\Company\Structure
     */
    private $structure;

    /**
     * @var \Javid\Company\Api\StatusServiceInterface
     */
    private $moduleConfig;

    /**
     * CollectionFactory constructor.
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Javid\Company\Model\Company\Structure $structure
     * @param \Javid\Company\Api\StatusServiceInterface $moduleConfig
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Javid\Company\Model\Company\Structure $structure,
        \Javid\Company\Api\StatusServiceInterface $moduleConfig
    ) {
        $this->objectManager = $objectManager;
        $this->structure = $structure;
        $this->moduleConfig = $moduleConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function create($customerId = null)
    {
        $collection = $this->objectManager->create($this->instanceName);

        if ($customerId) {
            $customers = [];

            if ($this->moduleConfig->isActive()) {
                $customers = $this->structure->getAllowedChildrenIds($customerId);
            }

            $customers[] = $customerId;
            $collection->addFieldToFilter('customer_id', ['in' => $customers]);
        }

        return $collection;
    }
}
