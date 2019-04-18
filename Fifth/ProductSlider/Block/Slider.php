<?php
namespace Fifth\ProductSlider\Block;

class Slider extends \Magento\Framework\View\Element\Template
{
    protected $_productCollectionFactory;
    protected $_productloader;
    protected $sliderHelper;
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $productloader,
        \Fifth\ProductSlider\Helper\Data $helperData,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
             $this->_productCollectionFactory = $productCollectionFactory;
             $this->_productloader = $productloader;
             $this->sliderHelper = $helperData ;
            $this->_storeManager = $storeManager;
        parent::__construct($context);
      }

    public function getProductCollection() {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');

        // filter current website products
        $collection->addWebsiteFilter();

        // filter current store products
        $collection->addStoreFilter();

        // fetching only 5 products
        $collection->setPageSize(9);

        return $collection;
    }
    public function getProductById($id){
        return $this->_productloader->create()->load($id);
    }

    public function  getJsPart(){
       return $this->getLayout()
                   ->createBlock("Magento\Framework\View\Element\Template")
                   ->setTemplate("Fifth_ProductSlider::js/slider.phtml")->toHtml();
    }


    public function getSliderHelper()
    {
        return $this->sliderHelper;
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