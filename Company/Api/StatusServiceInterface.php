<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Api;

use Magento\Store\Model\ScopeInterface;

/**
 * Service Status interface
 *
 * @api
 * @since 100.0.0
 */
interface StatusServiceInterface
{
    /**
     * Is module active.
     *
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return bool
     */
    public function isActive($scopeType = ScopeInterface::SCOPE_WEBSITE, $scopeCode = null);

    /**
     * Is company registration from the storefront allowed.
     *
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return bool
     */
    public function isStorefrontRegistrationAllowed($scopeType = ScopeInterface::SCOPE_WEBSITE, $scopeCode = null);
}
