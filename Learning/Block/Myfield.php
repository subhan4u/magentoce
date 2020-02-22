<?php

namespace Javid\Learning\Block;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Myfield extends Template {

    /**
     * @var ShippingInformationInterface
     */
    private $_addressInformation;

    /**
     * @param Context $context
     * @param ShippingInformationInterface $addressInformation
     * @param array $data
     */
    public function __construct(
        Context $context,
        ShippingInformationInterface $addressInformation,
        array $data = []
    ) {
        $this->_addressInformation = $addressInformation;
        parent::__construct($context, $data);
    }

    /**
     * Get custom Shipping Delivery address
     *
     * @return String
     */
    public function getShippingdelivery()
    {
        $extAttributes = $this->_addressInformation->getExtensionAttributes();
        return $extAttributes->getSdelivery(); //get custom attribute data.
    }

    /**
     * Get custom Billing Delivery address
     *
     * @return String
     */
    public function getBillingdelivery()
    {
        $extAttributes = $this->_addressInformation->getExtensionAttributes();
        return $extAttributes->getBdelivery(); //get custom attribute data.
    }
}
