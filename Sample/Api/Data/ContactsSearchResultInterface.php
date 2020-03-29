<?php

namespace Javid\Sample\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ContactsSearchResultInterface extends SearchResultsInterface 
{
    /**
     * @return \Javid\Sample\Api\Data\ContactsInterface[]
     */
    public function getItems();

    /**
     * @param \Javid\Sample\Api\Data\ContactsInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}