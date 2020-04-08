<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Model\Customer\Source;

use Javid\Company\Api\Data\CompanyCustomerInterface;

/**
 * Class Status
 */
class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['label' => __('Active'), 'value' => CompanyCustomerInterface::STATUS_ACTIVE],
            ['label' => __('Inactive'), 'value' => CompanyCustomerInterface::STATUS_INACTIVE]
        ];
    }
}
