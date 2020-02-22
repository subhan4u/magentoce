<?php

namespace Javid\Learning\Model;

class AdditionalConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
   /**
	 * @var \Magento\Framework\App\Config\ScopeConfigInterface
	 * @deprecated 100.2.0
	 */
   protected $scopeConfig;
    
   public function __construct(
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
		)
	{
		$this->scopeConfig = $scopeConfig;
	}
    
    public function getConfig()
    {
      //$scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;
      $output['googlekey'] = $this->scopeConfig->getValue( 'javidlearning/general/googleapikey');
      return $output;
    }
}