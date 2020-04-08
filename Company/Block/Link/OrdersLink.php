<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Block\Link;

use Magento\Customer\Block\Account\SortLinkInterface;

/**
 * Link for company admin and b2c customers.
 *
 * @api
 * @since 100.0.0
 */
class OrdersLink extends \Magento\Framework\View\Element\Html\Link\Current implements SortLinkInterface
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
     * @var string
     */
    private $resource;

    /**
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
        parent::__construct($context, $defaultPath, $data);
        $this->companyContext = $companyContext;
        $this->companyManagement = $companyManagement;
        if (isset($data['resource'])) {
            $this->resource = $data['resource'];
        }
    }

    /**
     * View link My orders in customer menu.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->isVisible()) {
            return parent::_toHtml();
        }

        return '';
    }

    /**
     * Check visible link for company admin and b2c customers.
     *
     * @return bool
     */
    private function isVisible()
    {
        $company = null;
        if ($this->companyContext->getCustomerId()) {
            $company = $this->companyManagement->getByCustomerId($this->companyContext->getCustomerId());
        }

        return !$company || $this->companyContext->isResourceAllowed($this->resource);
    }

    /**
     * @inheritdoc
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }
}
