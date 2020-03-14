<?php
namespace Javid\MagentoSessions\Controller\Custom;

class Page extends \Magento\Framework\App\Action\Action 
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}