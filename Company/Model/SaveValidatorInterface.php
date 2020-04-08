<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model;

/**
 * Company save validator interface.
 */
interface SaveValidatorInterface
{
    /**
     * Execute save validator.
     *
     * @return void
     */
    public function execute();
}
