<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\SaveValidator;

use Javid\Company\Api\Data\CompanyInterface;

/**
 * Checks if company rejected fields are correct.
 */
class RejectedFields implements \Javid\Company\Model\SaveValidatorInterface
{
    /**
     * @var \Javid\Company\Api\Data\CompanyInterface
     */
    private $company;

    /**
     * @var \Magento\Framework\Exception\InputException
     */
    private $exception;

    /**
     * @var \Javid\Company\Api\Data\CompanyInterface
     */
    private $initialCompany;

    /**
     * @param \Javid\Company\Api\Data\CompanyInterface $company
     * @param \Javid\Company\Api\Data\CompanyInterface $initialCompany
     * @param \Magento\Framework\Exception\InputException $exception
     */
    public function __construct(
        \Javid\Company\Api\Data\CompanyInterface $company,
        \Javid\Company\Api\Data\CompanyInterface $initialCompany,
        \Magento\Framework\Exception\InputException $exception
    ) {
        $this->company = $company;
        $this->initialCompany = $initialCompany;
        $this->exception = $exception;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if (($this->company->getRejectedAt() != $this->initialCompany->getRejectedAt()
                || $this->company->getRejectReason() != $this->initialCompany->getRejectReason())
            && !($this->company->getStatus() == CompanyInterface::STATUS_REJECTED
                && $this->initialCompany->getStatus() != CompanyInterface::STATUS_REJECTED)
        ) {
            $this->exception->addError(
                __(
                    'Invalid attribute value. Rejected date&time and Rejected Reason can be changed only'
                    . ' when a company status is changed to Rejected.'
                )
            );
        }
    }
}
