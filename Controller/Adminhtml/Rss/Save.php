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

namespace MrSignage\Rss\Controller\Adminhtml\Rss;

use Magento\Framework\Exception\LocalizedException;
use MrSignage\Rss\Model\ResourceModel\Rss\CollectionFactory;

/**
 * Class Save
 *
 * @category Save
 * @package  MrSignage\Rss\Controller\Adminhtml\Rss
 * @author   MrSignage.com <support@mrsingage.com>
 * @license  https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3 (AGPL-3.0)
 * @link     https://MrSignage.com
 */
class Save extends \Magento\Backend\App\Action
{

    /**
     * DataPersistor
     *
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * Model
     *
     * @var \MrSignage\Rss\Model\Rss
     */
    protected $model;

    /**
     * Collection
     *
     * @var CollectionFactory
     */
    protected $collection;

    /**
     * Construct 
     *
     * @param \Magento\Backend\App\Action\Context                   $context           context
     * @param \MrSignage\Rss\Model\Rss                              $model             model
     * @param CollectionFactory                                     $CollectionFactory CollectionFactory
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor     dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \MrSignage\Rss\Model\Rss $model,
        CollectionFactory $CollectionFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->model = $model;
        $this->collection = $CollectionFactory->create();
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {                                                                                                                                           
        $resultRedirect = $this->resultRedirectFactory->create();
        $datapost = $this->getRequest()->getPostValue();
        $data = $datapost['general'];

        if ($data) {
            try{
                
                $item = $this->collection->getFirstItem();
                //echo "<pre>"; print_r($item->getData()); die;
                $model = $this->model->load($item->getId());

                $model->setData($data);    
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Rss Feed.'));
                $this->dataPersistor->clear('mrsignage_rss_rss');
                return $resultRedirect->setPath('*/*/');
                
            }catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Rss Feed.'));
            }

            $this->dataPersistor->set('mrsignage_rss_rss', $data);
        }

        return $resultRedirect->setPath('*/*/');
        
    }
}
