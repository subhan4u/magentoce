<?php
namespace Javid\Sample\Block;

use Javid\Sample\Model\Contacts as blkContacts;
use Magento\Framework\View\Element\Template;

class Contacts extends \Magento\Framework\View\Element\Template 
{
    private $_contacts;

    /**
     *
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        blkContacts $contacts,
        array $data = array()
    ) 
    {
        $this->_contacts = $contacts;
        parent::__construct($context, $data);
        $this->setData('contacts',array());
    }

    public function addContks($count) {
        $_contacts = $this->getData('contacts');
        $cont = count($_contacts);
        for($i=$cont; $i<($cont+$count);$i++) {
            $_contacts[] = $i+1;
        }
        $this->setData('contacts',$_contacts);
    }

    public function contactsBlock() {
        return $this->_contacts->getCollection();
    }
}