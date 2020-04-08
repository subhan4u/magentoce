<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\SaveHandler;

use Javid\Company\Model\SaveHandlerInterface;
use Javid\Company\Api\Data\CompanyInterface;

/**
 * Company sales representative save handler.
 */
class SalesRepresentative implements SaveHandlerInterface
{
    /**
     * @var \Javid\Company\Model\Email\Sender
     */
    private $companyEmailSender;

    /**
     * @param \Javid\Company\Model\Email\Sender $companyEmailSender
     */
    public function __construct(
        \Javid\Company\Model\Email\Sender $companyEmailSender
    ) {
        $this->companyEmailSender = $companyEmailSender;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(CompanyInterface $company, CompanyInterface $initialCompany)
    {
        if ($initialCompany->getSalesRepresentativeId() != $company->getSalesRepresentativeId()) {
            $this->companyEmailSender->sendSalesRepresentativeNotificationEmail(
                $company->getId(),
                $company->getSalesRepresentativeId()
            );
        }
    }
}
