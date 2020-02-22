<?php
namespace Javid\Learning\Controller\Product;

class Sublevel extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $_pageFactory
    )
    {
        $this->_pagebuild = $_pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        return $this->_pagebuild->create();
    }
}