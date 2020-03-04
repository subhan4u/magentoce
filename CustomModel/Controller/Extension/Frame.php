<?php
namespace Javid\CustomModel\Controller\Extension;
 
class Frame extends \Magento\Framework\App\Action\Action
{
    protected $resultJsonFactory;
    
    protected $regionColFactory;
 
            	public function __construct(
            	\Magento\Framework\App\Action\Context $context,
            	\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
            	\Magento\Directory\Model\RegionFactory $regionColFactory)
    {      	
            	$this->regionColFactory            	= $regionColFactory;
            	$this->resultJsonFactory = $resultJsonFactory;
            	parent::__construct($context);
            	}
    
            	public function execute()
            	{
            	 $result = $this->resultJsonFactory->create();
            	
            	 $html='<option selected="selected" value="">Please Select Option</option>';
            	
            	$frameName = $this->getRequest()->getParam('frame');
            	 if($frameName!='')
            	 {
                            	 switch ($frameName)
                            	 {
                                            case 'value1' :
                                                     $html.='<option value="Option 1">Title 1</option>';
                                                     $html.='<option value="Option 2">Title 2</option>';
                                                     break;
                                                            	 
                                            case 'value2' :
                                                     $html.='<option value="Option 1">Title 1</option>';
                                                     $html.='<option value="Option 2">Title 2</option>';
                                                     Break;

                                            // handle other switch case as per your requirements
                                                            	
                            	 }    
            	 }
            	
            	 return $result->setData(['success' => true,'value'=>$html]);
   }
}