<?php
namespace Javid\MagentoSessions\Block;

class Sessions extends \Magento\Framework\View\Element\Template
{
    protected $_customerSession;
    protected $_catalogSession;
    protected $_checkoutSession;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Catalog\Model\Session $catalogSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data = []
    )
    {
        $this->_customerSession = $customerSession;
        $this->_catalogSession = $catalogSession;
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context,$data);
    }

    public function _prepareLayout() 
    {
        return parent::_prepareLayout();
    }

    public function getCustomerSession() 
    {
        return $this->_customerSession;
    }

    public function getCatalogSession() 
    {
        return $this->_catalogSession;
    }

    public function getCheckoutSession() 
    {
        return $this->_checkoutSession;
    }
}