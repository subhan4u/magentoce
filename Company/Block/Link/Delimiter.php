<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Block\Link;

/**
 * Delimiter for account navigation.
 *
 * @api
 * @since 100.0.0
 */
class Delimiter extends \Magento\Framework\View\Element\Template
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
     * @var array
     */
    private $resources;

    /**
     * CompanyLink constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Javid\Company\Model\CompanyContext $companyContext
     * @param \Javid\Company\Api\CompanyManagementInterface $companyManagement
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Javid\Company\Model\CompanyContext $companyContext,
        \Javid\Company\Api\CompanyManagementInterface $companyManagement,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->companyContext = $companyContext;
        $this->companyManagement = $companyManagement;
        $this->resources = isset($data['resources']) && is_array($data['resources'])
            ? array_values($data['resources'])
            : [];
    }

    /**
     * Return HTML only if block is visible.
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
     * Determine if the block is visible.
     *
     * @return bool
     */
    private function isVisible()
    {
        $company = null;
        if ($this->companyContext->getCustomerId()) {
            $company = $this->companyManagement->getByCustomerId($this->companyContext->getCustomerId());
        }
        $isRegistrationAllowed = $this->companyContext->isStorefrontRegistrationAllowed();

        $isVisible = false;
        $isActive = $this->companyContext->isModuleActive();
        if ($isActive) {
            $isVisible = !$company && $isRegistrationAllowed || $this->isResourceAllowed();
        }

        return $isVisible;
    }

    /**
     * Determine if any of assigned resources is allowed.
     *
     * @return bool
     */
    private function isResourceAllowed()
    {
        $result = false;
        foreach ($this->resources as $resource) {
            if ($this->companyContext->isResourceAllowed($resource) === true) {
                $result = true;
                break;
            }
        }

        return $result;
    }
}
