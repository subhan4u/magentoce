<?php
namespace Javid\Learning\Block;

class Newpage extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    )
    {
        parent::__construct($context);
    }

    public function displayNewpage()
    {
        return __("This is yet another new page");
    }
}