<?php
namespace Javid\Sample\Model;

use Magento\Framework\Model\AbstractModel;

class Contacts extends AbstractModel 
{

    protected function _construct()
    {
        $this->_init(\Javid\Sample\Model\ResourceModel\Contacts::class);
    }
}