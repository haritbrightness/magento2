<?php

/**
 * The MrSignage Magento extension will enable the store owner to display a configurable product feed on their MrSignage powered digital signage screens.
 *
 * Php version 7.3.14
 * 
 * @category  MrSignage
 * @package   MrSignage_Rss
 * @author    MrSignage.com <support@mrsingage.com>
 * @copyright 2020 Brightness Digital Signage Solutions B.V. (MrSignage.com)
 * @license   https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3 (AGPL-3.0)
 * @link      https://MrSignage.com
 */

namespace MrSignage\Rss\Model\Rss;

use Magento\Framework\App\Request\DataPersistorInterface;
use MrSignage\Rss\Model\ResourceModel\Rss\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class DataProvider
 *
 * @category MrSignage
 * @package  MrSignage\Rss\Model\Rss
 * @author   MrSignage.com <support@mrsingage.com>
 * @license  https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3 (AGPL-3.0)
 * @link     https://MrSignage.com
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    private $_loadedData;
    
    /**
     * DataPersistor
     *
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    private $_dataPersistor;

    /**
     * CollectionFactory
     *
     * @var CollectionFactory
     */
    public $collection;
     
    /**
     * Constructor For DataProvide in Form
     *
     * @param string                 $name              name
     * @param string                 $primaryFieldName  primaryFieldName
     * @param string                 $requestFieldName  requestFieldName
     * @param CollectionFactory      $collectionFactory collectionFactory
     * @param DataPersistorInterface $dataPersistor     dataPersistor
     * @param StoreManagerInterface  $storeManager      storeManager
     * @param array                  $meta              meta
     * @param array                  $data              data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->_dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct(
            $name, 
            $primaryFieldName, 
            $requestFieldName, 
            $meta, 
            $data
        );
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        $items = $this->collection->getFirstItem();

        if ($items->getId()) {
            $this->_loadedData[$items->getId()]['general'] = $items->getData();
        }

        return $this->_loadedData;
    }
}
