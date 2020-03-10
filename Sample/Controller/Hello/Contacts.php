<?php
namespace Javid\Sample\Controller\Hello;

use Magento\Framework\App\Action\Action;

class Contacts extends Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}