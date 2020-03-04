<?php

namespace Javid\DependentFields\Controller\Example;

/**
 * Class Index
 * @package Javid\DependentFields\Controller\Example
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    /**
     * @inheritdoc
     */
    public function execute()
    {
        return  $resultPage = $this->resultPageFactory->create();
    }
}
