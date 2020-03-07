<?php
namespace Javid\Sample\Model\ResourceModel\Contacts;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection 
{
    public function _construct()
    {
        $this->_init('Javid\Sample\Model\Contacts','Javid\Sample\Model\ResourceModel\Contacts');
    }
}