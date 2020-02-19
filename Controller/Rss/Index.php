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

namespace MrSignage\Rss\Controller\Rss;

use Magento\Framework\Exception\NotFoundException;

/**
 * Class Index
 *
 * @category Rss
 * @package  MrSignage\Rss\Controller\Rss
 * @author   MrSignage.com <support@mrsingage.com>
 * @license  https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3 (AGPL-3.0)
 * @link     https://MrSignage.com
 */
class Index extends \MrSignage\Rss\Controller\Rss
{
    /**
     * Index action
     *
     * @return void
     * @throws NotFoundException
     */
    public function execute()
    {   
        $this->response->setHeader('Access-Control-Allow-Origin', '*', true);
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS', true);
        $this->response->setHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization,X-Requested-With', true);
        $result = $this->resultJsonFactory->create();
        if (!$this->scopeConfig->getValue('mrsignage_rss/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            throw new NotFoundException(__('Page not found.'));
        }
        try{
            $response = $this->rss->getRssProductData($this->getRequest()->getParam('limit', 50));
        }catch(\Exception $e){
            $response = ['error'=>$e->getMessage()];
        }

        $result->setData($response);
        return $result;
    }
}
