<?php

namespace Javid\Learning\Controller\Hello;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;
    
    /**
     *  Constructor
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $_pageFactory
     */
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $_pageFactory
    ) {
        $this->_page = $_pageFactory;
        return parent::__construct($context);
    }
    
    public function execute()
    {
        return $this->_page->create();
    }
}
