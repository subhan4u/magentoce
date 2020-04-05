<?php

namespace Javid\ProductDetails\Api;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * This API gets product details by its Id
 */
interface ProductRepositoryInterface 
{
    /**
     * Get Product by its Id
     *
     * @param int $id
     * @return \Javid\ProductDetails\Api\Data\ProductInterface
     * @throws NoSuchEntityException
     */
    public function getProductById($id);
}