<?php
namespace Javid\DependentFields\Model\Example;

/**
 * Class Dropdproducts
 * @package Javid\DependentFields\Model\Dropdproducts
 */
class Dropdproducts implements \Magento\Framework\Option\ArrayInterface
{
    /**
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $collectionFactory;

    /** @var array */
    private $items;

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        if (is_null($this->items)) {
            $this->items = $this->getProducts();
        }

        return $this->items;
    }


    /**
     *
     * @return array
     */
    public function getProducts() {
        $items = [];
        //print_r($this->getCollection()->getSelect()->__toString());
        foreach ($this->getCollection() as $prods) {
            $items[] = [
                'label' => $prods->getName(), 'value' => $prods->getId()
            ];
        }

        return $items;
    }

    /**
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    private function getCollection() {
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect('*');
        return $collection;
    }
}
