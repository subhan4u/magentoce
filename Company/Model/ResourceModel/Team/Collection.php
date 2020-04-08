<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Model\ResourceModel\Team;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Company collection
 */
class Collection extends AbstractCollection
{
    /**
     * Standard collection initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Javid\Company\Model\Team::class, \Javid\Company\Model\ResourceModel\Team::class);
    }
}
