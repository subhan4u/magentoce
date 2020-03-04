<?php
namespace Javid\CustomModel\Block;
 
class DependentDropdown extends \Magento\Framework\View\Element\Template
{
    protected $_isScopePrivate;
    
    public function __construct(
           	\Magento\Framework\View\Element\Template\Context $context,
           	array $data = []
            	)
            	{
            	parent::__construct($context, $data);
            	$this->_isScopePrivate = true;
            	}
 
    public function getframeAction()
            	{
            	return $this->getUrl('pagea/extension/frame', ['_secure' => true]);
            	}
}