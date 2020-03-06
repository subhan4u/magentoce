<?php
namespace Javid\Sample\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Contacts extends AbstractDb
{
    public function __construct()
    {
        $this->_init('pfay_contacts','pfay_contacts_id');
    }
}