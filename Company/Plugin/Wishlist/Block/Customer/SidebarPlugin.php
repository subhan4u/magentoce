<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Plugin\Wishlist\Block\Customer;

use Magento\Wishlist\Block\Customer\Sidebar;

/**
 * Plugin for Wishlist Sidebar class.
 */
class SidebarPlugin
{
    /**
     * @var \Javid\Company\Model\CompanyContext
     */
    private $companyContext;

    /**
     * @var \Javid\Company\Api\CompanyManagementInterface
     */
    private $companyManagement;

    /**
     * SidebarPlugin constructor.
     *
     * @param \Javid\Company\Model\CompanyContext $companyContext
     * @param \Javid\Company\Api\CompanyManagementInterface $companyManagement
     */
    public function __construct(
        \Javid\Company\Model\CompanyContext $companyContext,
        \Javid\Company\Api\CompanyManagementInterface $companyManagement
    ) {
        $this->companyContext = $companyContext;
        $this->companyManagement = $companyManagement;
    }

    /**
     * Around to html.
     *
     * @param Sidebar $subject
     * @param \Closure $proceed
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundToHtml(Sidebar $subject, \Closure $proceed)
    {
        return $this->companyContext->getCustomerId() &&
               $this->companyManagement->getByCustomerId($this->companyContext->getCustomerId()) ? '' : $proceed();
    }
}