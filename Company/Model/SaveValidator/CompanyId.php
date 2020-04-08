<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\SaveValidator;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Checks if company id is correct.
 */
class CompanyId implements \Javid\Company\Model\SaveValidatorInterface
{
    /**
     * @var \Javid\Company\Api\Data\CompanyInterface
     */
    private $company;

    /**
     * @var \Javid\Company\Api\Data\CompanyInterface
     */
    private $initialCompany;

    /**
     * @param \Javid\Company\Api\Data\CompanyInterface $company
     * @param \Javid\Company\Api\Data\CompanyInterface $initialCompany
     */
    public function __construct(
        \Javid\Company\Api\Data\CompanyInterface $company,
        \Javid\Company\Api\Data\CompanyInterface $initialCompany
    ) {
        $this->company = $company;
        $this->initialCompany = $initialCompany;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if ($this->company->getId() && !$this->initialCompany->getId()) {
            throw new NoSuchEntityException(
                __(
                    'No such entity with %fieldName = %fieldValue',
                    [
                        'fieldName' => 'companyId',
                        'fieldValue' => $this->company->getId()
                    ]
                )
            );
        }
    }
}
