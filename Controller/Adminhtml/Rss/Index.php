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

use MrSignage\Rss\Model\ResourceModel\Rss\CollectionFactory;

/**
 * Class Index
 *
 * @category Index
 * @package  MrSignage\Rss\Controller\Adminhtml\Rss
 * @author   MrSignage.com <support@mrsingage.com>
 * @license  https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3 (AGPL-3.0)
 * @link     https://MrSignage.com
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    private $_coreRegistry = null;

    /**
     * ResultPageFactory
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Collection
     *
     * @var CollectionFactory
     */
    protected $collection;

    /**
     * Construct
     *
     * @param \Magento\Backend\App\Action\Context        $context           context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory resultPageFactory
     * @param CollectionFactory                          $CollectionFactory CollectionFactory
     * @param \Magento\Framework\Registry                $registry          registry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        CollectionFactory $CollectionFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry     = $registry;
        $this->collection     = $CollectionFactory->create();
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        $item = $this->collection->getFirstItem();
        if ($item->getId()) {
            $this->getRequest()->setParam('id', $item->getId());
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MrSignage_Rss::editcatalog')
            ->addBreadcrumb(__('MrSignage Setup'), __('MrSignage Setup'))
            ->addBreadcrumb(__('Rss'), __('Rss'));
        return $resultPage;
    }

     /**
      * Check the permission to run it
      *
      * @return bool
      */
    protected function _isAllowed()
    {
        return true;

    }//end _isAllowed()
}
