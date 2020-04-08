<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for company team search results
 *
 * @api
 * @since 100.0.0
 */
interface TeamSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get teams list
     *
     * @return \Javid\Company\Api\Data\TeamInterface[]
     */
    public function getItems();

    /**
     * Set teams list
     *
     * @param \Javid\Company\Api\Data\TeamInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
