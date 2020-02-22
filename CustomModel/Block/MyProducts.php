<?php
namespace Javid\CustomModel\Block;

class MyProducts extends \Magento\Framework\View\Element\Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }

    public function productselection()
    {
        return __("All Products");
    }
}