<?php

namespace Javid\Sample\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Javid\Sample\Api\Data\ContactsInterface;

interface ContactsRepositoryInterface 
{
    /**
     * @param int $pfayid
     * @return \Javid\Sample\Api\Data\ContactsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($pfayid);

    /**
     * @param \Javid\Sample\Api\Data\ContactsInterface $contacts
     * @return \Javid\Sample\Api\Data\ContactsInterface
     */
    public function save(ContactsInterface $contacts);

    /**
     * @param \Javid\Sample\Api\Data\ContactsInterface $contacts
     * @return void
     */
    public function delete(ContactsInterface $contacts);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Javid\Sample\Api\Data\ContactsSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}