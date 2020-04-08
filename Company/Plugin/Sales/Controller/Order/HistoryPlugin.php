<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Plugin\Sales\Controller\Order;

/**
 * Class HistoryPlugin. We use it to check permissions.
 */
class HistoryPlugin
{
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    private $resultRedirectFactory;

    /**
     * @var \Javid\Company\Api\AuthorizationInterface
     */
    private $authorization;

    /**
     * @var \Javid\Company\Model\CompanyContext
     */
    private $companyContext;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     * @param \Javid\Company\Api\AuthorizationInterface $authorization
     * @param \Javid\Company\Model\CompanyContext $companyContext
     */
    public function __construct(
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Javid\Company\Api\AuthorizationInterface $authorization,
        \Javid\Company\Model\CompanyContext $companyContext
    ) {
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->authorization = $authorization;
        $this->companyContext = $companyContext;
    }

    /**
     * Here we check permissions.
     *
     * @param \Magento\Sales\Controller\Order\History $subject
     * @param \Magento\Framework\View\Result\Page $result
     * @return \Magento\Framework\View\Result\Page
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterExecute(
        \Magento\Sales\Controller\Order\History $subject,
        \Magento\Framework\View\Result\Page $result
    ) {
        if (!$this->authorization->isAllowed('Magento_Sales::all')) {
            $resultRedirect = $this->resultRedirectFactory->create();

            if ($this->companyContext->isModuleActive() && $this->companyContext->isCurrentUserCompanyUser()) {
                $result = $resultRedirect->setPath('company/accessdenied');
            }
        }

        return $result;
    }
}
