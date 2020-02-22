<?php

namespace Javid\CustomModel\Controller\Secondpage;

class Indexx extends \Magento\Framework\App\Action\Action
{
    /*
    protected $_page;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $_page)
    {
        $this->_page = $_page;
        return parent::__construct($context);
    }
    */
    
    public function execute()
    {
        echo __("hi javid controller");
    }
}