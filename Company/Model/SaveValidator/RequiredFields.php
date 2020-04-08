<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\SaveValidator;

use \Javid\Company\Api\Data\CompanyInterface;

/**
 * Checks if company has all required fields.
 */
class RequiredFields implements \Javid\Company\Model\SaveValidatorInterface
{
    /**
     * @var array
     */
    private $requiredFields = [
        CompanyInterface::NAME,
        CompanyInterface::COMPANY_EMAIL,
        CompanyInterface::STREET,
        CompanyInterface::CITY,
        CompanyInterface::POSTCODE,
        CompanyInterface::TELEPHONE,
        CompanyInterface::COUNTRY_ID,
        CompanyInterface::SUPER_USER_ID,
        CompanyInterface::CUSTOMER_GROUP_ID
    ];

    /**
     * @var \Javid\Company\Api\Data\CompanyInterface
     */
    private $company;

    /**
     * @var \Magento\Framework\Exception\InputException
     */
    private $exception;

    /**
     * @param \Javid\Company\Api\Data\CompanyInterface $company
     * @param \Magento\Framework\Exception\InputException $exception
     */
    public function __construct(
        \Javid\Company\Api\Data\CompanyInterface $company,
        \Magento\Framework\Exception\InputException $exception
    ) {
        $this->company = $company;
        $this->exception = $exception;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        foreach ($this->requiredFields as $field) {
            if (empty($this->company->getData($field))) {
                $this->exception->addError(
                    __(
                        '"%fieldName" is required. Enter and try again.',
                        ['fieldName' => $field]
                    )
                );
            }
        }
    }
}
