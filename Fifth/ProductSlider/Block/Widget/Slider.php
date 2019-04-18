<?php

namespace  Fifth\ProductSlider\Block\Widget;

use Magento\Catalog\Block\Product\Context;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Fifth\ProductSlider\Helper\Data;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
/**
 * Class Slider
 * @package Fifth\ProductSlider\Block\Widget
 */
class Slider extends Template implements BlockInterface
{

    protected $_template = "widget/slider.phtml";
    protected $_helperData;
    protected $_productloader;
    protected $_productCollectionFactory;
    protected $_storeManager;

    /**
     * Slider constructor.
     * @param Context $context
     * @param CollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        Data $_helperData,
        ProductFactory $productloader,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productloader = $productloader;
        $this->_helperData = $_helperData;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);

    }



    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductCollection()
    {
               // monthly bestseller products
        if ($this->getData( "product_type")  == "best-seller"){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $productCollection = $objectManager->create('Magento\Reports\Model\ResourceModel\Report\Collection\Factory');
            $collection = $productCollection->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection');
            $collection->setPeriod('month');
           //$collection->setPeriod('year');
           //$collection->setPeriod('day');
        }else{
            $collection = $this->_productCollectionFactory->create();
            $collection->addAttributeToSelect('*');
            // filter current website products
            $collection->addWebsiteFilter($this->_storeManager->getStore()->getWebsiteId());
        }


        // filter current store products
        $collection->addStoreFilter($this->getStoreId());
        // filter new products
        if ($this->getData( "product_type") == "new"){
            $todayDate  = date('Y-m-d', time());
          $collection->addAttributeToFilter('news_from_date', array('date' => true, 'to' => $todayDate));
        }
          $collection->setPageSize($this->getCurrentPage());
        return $collection;
    }


    public function getHelperData()
    {
         return $this->_helperData;
    }


    /**
     * Get number of current page based on query value
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return abs((int)$this->getRequest()->getParam($this->getData('page_var_name')));
    }

    /**
     * Retrieve how many products should be displayed on page
     *
     * @return int
     */
    protected function getPageSize()
    {
        return $this->getProductsCount();
    }

    public function getProductById($id){
        return $this->_productloader->create()->load($id);
    }

    /**
     * Get limited number
     * @return int|mixed
     */
    public function getProductsCount()
    {
        return $this->getData('products_count')?: 10;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->getData('title');
    }


    public function  getJsPart(){
        return $this->getLayout()
            ->createBlock("Magento\Framework\View\Element\Template")
            ->setTemplate("Fifth_ProductSlider::js/slider.phtml")->toHtml();
    }

    /**
     * Get Store Id
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
}