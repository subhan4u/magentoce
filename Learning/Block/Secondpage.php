<?php

namespace Javid\Learning\Block;

class Secondpage extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    ){
        parent::__construct($context);
    }

    public function secondMatter()
    {
        return __("How are you");
    }
}