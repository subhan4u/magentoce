<?php
namespace Javid\Sample\Controller\Adminhtml\Hello;
use Magento\Backend\App\Action;

class Index extends \Magento\Backend\App\Action
{
    public function execute()
    {
        /*
        $contactsObj = $this->_objectManager->create('Javid\Sample\Model\Contacts');
        $contactsObj->setName('Abdul Sayyed');
        $contactsObj->save();

        $contactsObj = $this->_objectManager->create('Javid\Sample\Model\Contacts');
        $contactsObj->setName('Sayyed Subhan Abdul')
                    ->setEmail('subhan.abdul@gmail.com')
                    ->setComment('my comment')->save();
        */
        $contactsObj = $this->_objectManager->create('Javid\Sample\Model\Contacts');            
        $collec = $contactsObj->getCollection()->addFieldToFilter('name', array('like'=>'%Subha%'));

        //print($collec->getSelect()->__toString())    ;
        foreach($collec as $col) {
            var_dump($col->getData());
        }

        die('test');
    }
}