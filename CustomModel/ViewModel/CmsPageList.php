<?php
namespace Javid\CustomModel\ViewModel;

use Magento\Framework\UrlInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Cms\Api\Data\PageInterface;

class CmsPageList implements ArgumentInterface
{
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * CmsPageList Constructor
     *
     * @param PageRepositoryInterface $pageRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param UrlInterface $url
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        UrlInterface $url
    ) {
        $this->pageRepository = $pageRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->url = $url;
    }

    /**
     * @return PageInterface
     */
    private function getItems()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $pageSearchResult = $this->pageRepository->getList($searchCriteria);
        return $pageSearchResult->getItems();
    }

    /**
     * @return string
     */
    public function getItemsJson()
    {
        $result = [];
        foreach($this->getItems() as $page) {
            $result[$page->getIdentifier()] = [
                'title' => $page->getTitle(),
                'url' => $this->url->getUrl($page->getIdentifier())
            ];
        }
        return json_encode($result);
    }
}