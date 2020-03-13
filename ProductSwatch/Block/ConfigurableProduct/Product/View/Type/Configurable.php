<?php
namespace Javid\ProductSwatch\Block\ConfigurableProduct\Product\View\Type;

use Magento\Framework\Json\DecoderInterface;
use Magento\Framework\Json\EncoderInterface;

class Configurable 
{
    protected $jsonEncoder;
    protected $jsonDecoder;
    protected $_productRepository;

    /**
     *
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface
     * @param EncoderInterface $encoderInterface
     * @param DecoderInterface $decoderInterface
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface,
        EncoderInterface $encoderInterface,
        DecoderInterface $decoderInterface
    )
    {
        $this->jsonEncoder = $encoderInterface;
        $this->jsonDecoder = $decoderInterface;
        $this->_productRepository = $productRepositoryInterface;
    }

    public function getProductById($id) 
    {
        return $this->_productRepository->getById($id);
    }

    public function aroundGetJsonConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        \Closure $proceed) 
    {
        $sname = [];
        $sdescription = [];

        $config = $proceed();
        $config = $this->jsonDecoder->decode($config);

        foreach($subject->getAllowProducts() as $prod) 
        {
            $id = $prod->getId();
            $product = $this->getProductById($id);

            $sname[$id] = $product->getName();
            $sdescription[$id] = $prod->getData('description');
            
        }
        $config['sname'] = $sname;
        $config['sdescription'] = $sdescription;
        return  $this->jsonEncoder->encode($config);
    }
}