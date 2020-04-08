<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model;

use Javid\Company\Api\Data\CompanyInterface;

/**
 * Company save handler pool.
 */
class SaveHandlerPool
{
    /**
     * @var SaveHandlerInterface[]
     */
    private $handlers;

    /**
     * @param SaveHandlerInterface[] $handlers [optional]
     */
    public function __construct(
        $handlers = []
    ) {
        $this->handlers = $handlers;
    }

    /**
     * Execute save handlers.
     *
     * @param CompanyInterface $company
     * @param CompanyInterface $initialCompany
     * @return array CompanyInterface errors
     */
    public function execute(CompanyInterface $company, CompanyInterface $initialCompany)
    {
        foreach ($this->handlers as $saveHandler) {
            if (!$saveHandler instanceof \Javid\Company\Model\SaveHandlerInterface) {
                throw new \InvalidArgumentException(__(
                    'Type %1 is not an instance of %2',
                    get_class($saveHandler),
                    \Javid\Company\Model\SaveHandlerInterface::class
                ));
            }
            $saveHandler->execute($company, $initialCompany);
        }
    }
}
