<?php

namespace Javid\ProductDetails\Model;

use Javid\ProductDetails\Api\Data\ProductInterfaceFactory;
use Javid\ProductDetails\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductRepository implements ProductRepositoryInterface  
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var $productInterfaceFactory
     */
    private $productInterfaceFactory;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        ProductInterfaceFactory $productInterfaceFactory
    )
    {
        $this->productRepository = $productRepository;
        $this->productInterfaceFactory = $productInterfaceFactory;
    }
    public function getProductById($id)
    {
        /** @var \Javid\ProductDetails\Api\Data\ProductInterface $productInterface */
        $productInterface = $this->productInterfaceFactory->create();
        try {
            $product = $this->productRepository->getById($id);
            $productInterface->setId($product->getId())
                             ->setName($product->getName())
                             ->setSku($product->getSku())
                             ->setPrice($product->getPrice());

            return $productInterface;

        } catch(NoSuchEntityException $e) {
            throw NoSuchEntityException::singleField('id',$id);
        }
    }
}