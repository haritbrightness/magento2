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

namespace MrSignage\Rss\Block\Adminhtml\Rss\Edit\Tab;

use MrSignage\Rss\Model\ResourceModel\Rss\CollectionFactory;

/**
 * Class SaveButton
 *
 * @category Product
 * @package  MrSignage\Rss\Block\Adminhtml\Rss\Edit\Tab
 * @author   MrSignage.com <support@mrsingage.com>
 * @license  https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3 (AGPL-3.0)
 * @link     https://MrSignage.com
 */

class Product extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Product Collection Factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;
    
    /**
     * Registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * Rss collection 
     *
     * @var CollectionFactory
     */
    protected $collection;

    protected $objectManager = null;
    /**
     * Grid Constructor
     *
     * @param \Magento\Backend\Block\Template\Context                        $context                  context
     * @param \Magento\Backend\Helper\Data                                   $backendHelper            backendHelper
     * @param \Magento\Framework\Registry                                    $registry                 registry
     * @param \Magento\Framework\ObjectManagerInterface                      $objectManager            objectManager
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory productCollectionFactory
     * @param CollectionFactory                                              $collectionFactory        collectionFactory
     * @param array                                                          $data                     data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->objectManager = $objectManager;
        $this->registry = $registry;
        $this->collection = $collectionFactory->create();
        parent::__construct($context, $backendHelper, $data);
    }
    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('productsGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');   
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setDefaultFilter(array('in_product' => 1));
    }
    /**
     * Add column filter to collection
     * 
     * @param object $column Currunt filter column
     *
     * @return object
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_product') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
    /**
     * Prepare collection
     *
     * @return object
     */
    protected function _prepareCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToSelect('price');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * Prepare Coloums 
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_product',
            [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_product',
                'align' => 'center',
                'index' => 'entity_id',
                'values' => $this->_getSelectedProducts(),
            ]
        );

        $this->addColumn(
            'entity_id',
            [
                'header' => __('Product ID'),
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'name',
            [
                'header' => __('Name'),
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'sku',
            [
                'header' => __('Sku'),
                'index' => 'sku',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'index' => 'price',
                'width' => '50px',
            ]
        );
        return parent::_prepareColumns();
    }
    /**
     * Grid Url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productgrid', ['_current' => true]);
    }
    /**
     * Row Url
     *
     * @param object $row row
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    /**
     * Retrieve product Id array
     *
     * @return array
     */
    protected function _getSelectedProducts()
    {
        $productIds = $this->getProducts();
        $selected = explode('&', $productIds);
        return $selected;
    }

    /**
     * Retrieve selected products
     *
     * @return array
     */
    public function getSelectedProducts()
    {
        $productIds = $this->getProducts();

        $selected = explode('&', $productIds);
        if (!is_array($selected)) {
            $selected = [];
        }
        return $selected;
    }

    /**
     * Retrieve productIds from collection
     *
     * @return array
     */
    public function getProducts()
    {
        $item = $this->collection->getFirstItem();
        return $item->getProducts();
    }
}
