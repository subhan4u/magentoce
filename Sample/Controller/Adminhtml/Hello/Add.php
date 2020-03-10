<?php
namespace Javid\Sample\Controller\Adminhtml\Hello;

use Javid\Sample\Model\Contacts;
use Magento\Backend\App\Action;

class Add extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
        $contactForm = $this->getRequest()->getParams('contact');
        if(is_array($contactForm)) {
            $contactModel = $this->_objectManager->create(Contacts::class);
            $contactModel->setData($contactForm)->save();
            $resultObj = $this->resultRedirectFactory->create();
            return $resultObj->setPath('*/*/edit');
        }
    }
}