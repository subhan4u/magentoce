<?php
namespace Javid\Sample\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Javidsobserver implements ObserverInterface 
{
    public function execute(Observer $observer) 
    {
        die('observer got called');
    }
}