<?php
namespace Javid\Sample\Block;

use Magento\Framework\View\Element\Template;

class Contacts extends \Magento\Framework\View\Element\Template 
{
    /**
     *
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        array $data = array()
    ) 
    {
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
}