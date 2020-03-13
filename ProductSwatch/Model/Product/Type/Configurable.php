<?php
namespace Javid\ProductSwatch\Model\Product\Type;

class Configurable 
{
    public function afterGetUsedProductCollection(\Magento\ConfigurableProduct\Model\Product\Type\Configurable $subject, $result) 
    {
        $result->addAttributeToSelect('description');
        return $result;
    }
}