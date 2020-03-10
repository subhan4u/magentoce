<?php
namespace Javid\Sample\Controller\Adminhtml\Hello;

class Edit extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}