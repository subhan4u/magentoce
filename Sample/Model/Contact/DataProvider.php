<?php
namespace Javid\Sample\Model\Contact;

use Javid\Sample\Model\ResourceModel\Contacts\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider {

    /**
     *
     * @param [type] $name
     * @param [type] $primaryFieldName
     * @param [type] $requestFieldName
     * @param CollectionFactory $collection
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collection,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collection->create();
        parent::__construct($name,$primaryFieldName,$requestFieldName,$meta,$data);
    } 

    public function getData() {
        if(isset($this->loadedData)) {
            return $this->loadedData;
        }

        $this->loadedData = array();
        $items = $this->collection->getItems();
        foreach($items as $cont) {
            $this->loadedData[$cont->getId()]['contact'] = $cont->getData();
        }

        return $this->loadedData;
    }
}