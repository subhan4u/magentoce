<?php
namespace Javid\Sample\Controller\Adminhtml\Hello;
use Javid\Sample\Model\Contacts;

class MassDelete extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $ids = $this->getRequest()->getParam('selected', []);
        if (!is_array($ids) || !count($ids)) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/view', array('_current' => true));
        }
        foreach ($ids as $id) {
            if ($contact = $this->_objectManager->create(Contacts::class)->load($id)) {
                $contact->delete();
            }
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', count($ids)));

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/view', array('_current' => true));
    }
}