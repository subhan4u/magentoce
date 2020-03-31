<?php
namespace Javid\Sample\Model;

use Javid\Sample\Api\Data\ContactsInterface;
use Magento\Framework\Model\AbstractModel;

class Contacts extends AbstractModel implements ContactsInterface
{
    const PFAYID = 'pfay_contacts_id';
    const NAME = 'name';
    const EMAIL = 'email';

    protected function _construct()
    {
        $this->_init(\Javid\Sample\Model\ResourceModel\Contacts::class);
    }

    public function getPfayContactsId() 
    {
        return $this->_getData(self::PFAYID);
    }

    public function setPfayContactsId($pfayid) 
    {
        $this->setData(self::PFAYID,$pfayid);
    }

    public function getName() 
    {
        return $this->_getData(self::NAME);
    }

    public function setName($name) 
    {
        $this->setData(self::NAME,$name);
    }

    public function getEmail() 
    {
        return $this->_getData(self::EMAIL);
    }

    public function setEmail($email) 
    {
        $this->setData(self::EMAIL,$email);
    }
}