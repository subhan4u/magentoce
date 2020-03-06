<?php
namespace Javid\Sample\Controller\Hello;

class View extends \Magento\Framework\App\Action\Action 
{
    public function execute() {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}