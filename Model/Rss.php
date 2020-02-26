<?php

/**
 * The MrSignage Magento extension will enable the store owner to display a configurable product feed on their MrSignage powered digital signage screens.
 *
 * PHP version 7.3.14
 * 
 * @category  MrSignage
 * @package   MrSignage_Rss
 * @author    MrSignage.com <support@mrsingage.com>
 * @copyright 2020 Brightness Digital Signage Solutions B.V. (MrSignage.com)
 * @license   https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3 (AGPL-3.0)
 * @link      https://MrSignage.com
 */

namespace MrSignage\Rss\Model;

/**
 * Class Option
 *
 * @category Rss
 * @package  MrSignage\Rss\Model
 * @author   MrSignage.com <support@mrsingage.com>
 * @license  https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3 (AGPL-3.0)
 * @link     https://MrSignage.com
 */

class Rss extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Logger
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * ProductCollectionFactory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * ProductVisibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $productVisibility;

    /**
     * ProductStatus
     *
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $productStatus;

    /**
     * StoreManager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

     /**
     * galleryReadHandler
     *
     * @var \Magento\Catalog\Model\Product\Gallery\ReadHandler
     */
    protected $galleryReadHandler;

     /**
      * Helper
      *
      * @var \MrSignage\Rss\Helper\Data
      */
    protected $helper;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Model\Context                               $context                  context
     * @param \Magento\Framework\Registry                                    $registry                 registry
     * @param \MrSignage\Rss\Helper\Data                                     $helper                   helper
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource        $resource                 resource
     * @param \Magento\Framework\Data\Collection\AbstractDb                  $resourceCollection       resourceCollection
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Visibility                      $productVisibility        productVisibility
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status         $productStatus            productStatus
     * @param \Magento\Store\Model\StoreManagerInterface                     $storeManager             storeManager
     * @param \Magento\Catalog\Model\Product\Gallery\ReadHandler             $galleryReadHandler       galleryReadHandler
     * @param \Psr\Log\LoggerInterface                                       $logger                   logger
     * @param array                                                          $data                     data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \MrSignage\Rss\Helper\Data $helper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product\Gallery\ReadHandler $galleryReadHandler,
        \Psr\Log\LoggerInterface $logger,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productVisibility = $productVisibility;
        $this->productStatus = $productStatus;
        $this->storeManager = $storeManager;
        $this->galleryReadHandler = $galleryReadHandler;
        $this->logger = $logger;
        $this->helper = $helper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('MrSignage\Rss\Model\ResourceModel\Rss');
    }

    /**
     * Getproduct data
     *
     * @param string $limit limit
     *
     * @return array
     */
    public function getRssProductData($limit = null)
    {
        if ($this->helper->getConfig('mrsignage_rss/general/catalog_selection') == 1) {
            $collection = $this->getProductCollection($limit);
        }

        if ($this->helper->getConfig('mrsignage_rss/general/catalog_selection') == 2) {
            $collection = $this->getSelectedProductCollection($limit);
        }
        
        $result = [];
        
        foreach ($collection as $_product) {
            $product = [];
            $this->addGallery($_product);
            $product['name'] = $_product->getName(); 
            $product['description'] = $_product->getDescription(); 
            $product['short_description'] = $_product->getShortDescription(); 
            $product['price'] = $this->storeManager->getStore()->getBaseCurrency()->format($_product->getPrice(), [], false);
            if($_product->getSpecialPrice()){
                $product['special_price'] = $this->storeManager->getStore()->getBaseCurrency()->format($_product->getSpecialPrice(), [], false);
            }
            $product['low_price'] = $this->storeManager->getStore()->getBaseCurrency()->format($_product->getMinimalPrice(), [], false);
            $product['sku'] = $_product->getSku(); 
            $product['url'] = $_product->getProductUrl(); 
            $product['status'] = $_product->getStatus(); 
            $product['image'] = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'catalog/product'.$_product->getImage();
            $productImages = $_product->getMediaGalleryImages();
            $images = [];
            foreach ($productImages as $image) {
                $images[] = $image->getUrl(); 
            }
            $product['images'] = $images;
            $product['stock'] = $_product->getIsSalable();
            $result[] = $product;
        }

        return $result;
    }

    /**
     * Getproduct collection
     *
     * @param string $limit limit
     *
     * @return object
     */
    protected function getProductCollection($limit)
    {

        $collection = $this->productCollectionFactory->create();

        $collection->addAttributeToSelect('*');
    
        //set status filter
        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);

        // filter current website products
        $collection->addWebsiteFilter();
 
        // filter current store products
        $collection->addStoreFilter();
        $collection->addMinimalPrice();
  
        // set visibility filter
        $collection->setVisibility($this->productVisibility->getVisibleInSiteIds());

        // fetching only 50 products
        if ($limit != null) {
            $collection->setPageSize($limit); 
        }

        return $collection;
    }

    /**
     * Getselected product collection
     *
     * @param string $limit limit
     *
     * @return object
     */
    protected function getSelectedProductCollection($limit)
    {
        $item = $this->getCollection()->getFirstItem();

        $collection = $this->productCollectionFactory->create();
        
        $collection->addAttributeToSelect('*');

        if ($item->getId()) {
            if ($item->getEntityType() == 1) {
                if ($item->getCategory()) {
                    $ids = explode(',', $item->getCategory());
                    $collection->addCategoriesFilter(['in' => $ids]);
                } else {
                    return [];
                }
                
            } else if ($item->getEntityType() == 2) {
                if ($item->getProducts()) {
                    $ids = explode('&', $item->getProducts());
                    $collection->addFieldToFilter('entity_id', ['in' => $ids]);
                } else {
                    return [];
                }
            } else {
                return [];
            }
        } else {
            return [];
        }
        //set status filter
        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);

        // filter current website products
        $collection->addWebsiteFilter();
 
        // filter current store products
        $collection->addStoreFilter();
        $collection->addMinimalPrice();
  
        // set visibility filter
        $collection->setVisibility($this->productVisibility->getVisibleInSiteIds()); 

        if ($limit != null) {
            $collection->setPageSize($limit); 
        }

        return $collection;       
    }

    /**
     * Add Gallery for in product
     *
     * @param \Magento\Catalog\Model\Product $product product
     *
     * @return null
    */
    public function addGallery($product)
    {
        $this->galleryReadHandler->execute($product);
    }


}
