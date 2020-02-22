<?php

namespace Javid\Learning\Controller\Hello;

class Second extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory $_pageFactory;
     */
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $_pageFactory
    ){
        $this->_mypage = $_pageFactory;
        return parent::__construct($context);
    }

    public function execute(){
        return $this->_mypage->create();
    }
}