<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Block\Link;

/**
 * Class CompanyLink.
 *
 * @api
 * @since 100.0.0
 */
class CompanyLink extends Current implements \Magento\Customer\Block\Account\SortLinkInterface
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
     * CompanyLink constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\DefaultPathInterface $defaultPath
     * @param \Javid\Company\Model\CompanyContext $companyContext
     * @param \Javid\Company\Api\CompanyManagementInterface $companyManagement
     * @param array $data [optional]
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\DefaultPathInterface $defaultPath,
        \Javid\Company\Model\CompanyContext $companyContext,
        \Javid\Company\Api\CompanyManagementInterface $companyManagement,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $companyContext, $data);
        $this->companyContext = $companyContext;
        $this->companyManagement = $companyManagement;
    }

    /**
     * @return bool
     */
    protected function isVisible()
    {
        $company = null;
        $isRegistrationAllowed = $this->companyContext->isStorefrontRegistrationAllowed();
        if ($this->companyContext->getCustomerId()) {
            $company = $this->companyManagement->getByCustomerId($this->companyContext->getCustomerId());
        }
        return !$company && $isRegistrationAllowed || parent::isVisible();
    }

    /**
     * {@inheritdoc}
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }
}
