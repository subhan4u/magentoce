<?php
 
namespace Javid\Sample\Model;
 
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Javid\Sample\Api\Data\ContactsInterface;
use Javid\Sample\Api\Data\ContactsSearchResultInterface;
use Javid\Sample\Api\Data\ContactsSearchResultInterfaceFactory;
use Javid\Sample\Api\ContactsRepositoryInterface;
use Javid\Sample\Model\ResourceModel\Contacts\CollectionFactory as ContactsCollectionFactory;
use Javid\Sample\Model\ResourceModel\Contacts\Collection;
 
class ContactsRepository implements ContactsRepositoryInterface
{
    /**
     * @var contactsFactory
     */
    private $contactsFactory;
 
    /**
     * @var contactsCollectionFactory
     */
    private $contactsCollectionFactory;
 
    /**
     * @var contactsSearchResultInterfaceFactory
     */
    private $searchResultFactory;
 
    public function __construct(
        ContactsFactory $contactsFactory,
        ContactsCollectionFactory $contactsCollectionFactory,
        ContactsSearchResultInterfaceFactory $contactsSearchResultInterfaceFactory
    ) {
        $this->contactsFactory = $contactsFactory;
        $this->contactsCollectionFactory = $contactsCollectionFactory;
        $this->searchResultFactory = $contactsSearchResultInterfaceFactory;
    }
 
    public function getById($id)
    {
        $contacts = $this->contactsFactory->create();
        $contacts->getResource()->load($contacts, $id);
        if (! $contacts->getId()) {
            throw new NoSuchEntityException(__('Unable to find hamburger with ID "%1"', $id));
        }
        return $contacts;
    }
    
    public function save(ContactsInterface $contacts)
    {
        $contacts->getResource()->save($contacts);
        return $contacts;
    }
    
    public function delete(ContactsInterface $contacts)
    {
        $contacts->getResource()->delete($contacts);
    }
 
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
 
        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);
 
        $collection->load();
 
        return $this->buildSearchResult($searchCriteria, $collection);
    }
 
    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }
 
    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }
 
    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }
 
    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->searchResultFactory->create();
 
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
 
        return $searchResults;
    }
}