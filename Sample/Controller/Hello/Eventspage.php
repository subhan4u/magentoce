<?php
namespace Javid\Sample\Controller\Hello;

class Eventspage extends \Magento\Framework\App\Action\Action 
{
    public function execute() 
    {
        $this->_eventManager->dispatch('javids_event');
        die('test');
    }
}