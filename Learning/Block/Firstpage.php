<?php
namespace Javid\Learning\Block;

class Firstpage extends \Magento\Framework\View\Element\Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
	{
		parent::__construct($context);
    }
    
    public function mypage(){
        return __("Nice coding");
    }
}
