<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for company search results
 *
 * @api
 * @since 100.0.0
 */
interface CompanySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get companies list
     *
     * @return \Javid\Company\Api\Data\CompanyInterface[]
     */
    public function getItems();

    /**
     * Set companies list
     *
     * @param \Javid\Company\Api\Data\CompanyInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
