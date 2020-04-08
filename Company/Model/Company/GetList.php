<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\Company;

/**
 * Class for retrieveing lists of company model entities based on a given search criteria.
 */
class GetList
{
    /**
     * @var \Javid\Company\Model\ResourceModel\Company\CollectionFactory
     */
    private $companyCollectionFactory;

    /**
     * @var \Javid\Company\Api\Data\CompanySearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface
     */
    private $extensionAttributesJoinProcessor;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param \Javid\Company\Model\ResourceModel\Company\CollectionFactory $companyCollectionFactory
     * @param \Javid\Company\Api\Data\CompanySearchResultsInterfaceFactory $searchResultsFactory
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        \Javid\Company\Model\ResourceModel\Company\CollectionFactory $companyCollectionFactory,
        \Javid\Company\Api\Data\CompanySearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
    ) {
        $this->companyCollectionFactory = $companyCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Gets a list of companies.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Javid\Company\Api\Data\CompanySearchResultsInterface
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        /** @var \Javid\Company\Model\ResourceModel\Company\Collection $collection */
        $collection = $this->companyCollectionFactory->create();
        $this->extensionAttributesJoinProcessor->process($collection);

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }
}
