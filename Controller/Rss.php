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
namespace MrSignage\Rss\Controller;

use Magento\Framework\App\RequestInterface;
/**
 * Class Rss
 *
 * @category Rss
 * @package  MrSignage\Rss\Controller
 * @author   MrSignage.com <support@mrsingage.com>
 * @license  https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3 (AGPL-3.0)
 * @link     https://MrSignage.com
 */
abstract class Rss extends \Magento\Framework\App\Action\Action
{
    /**
     * Logger
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * ScopeConfig
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Rss
     *
     * @var \MrSignage\Rss\Model\Rss
     */
    protected $rss;

    /**
     * Response
     *
     * @var \MrSignage\Rss\Model\Rss
     */
    protected $response;

    /**
     * ResultFactory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * Construct
     *
     * @param \Magento\Framework\App\Action\Context              $context           context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig       scopeConfig
     * @param \MrSignage\Rss\Model\Rss                           $rss               rss
     * @param \Magento\Framework\Controller\Result\JsonFactory   $resultJsonFactory resultJsonFactory
     * @param \Magento\Framework\App\ResponseInterface           $response          response
     * @param \Psr\Log\LoggerInterface                           $logger            logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MrSignage\Rss\Model\Rss $rss,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->rss = $rss;
        $this->response = $response;
        $this->logger = $logger;
        parent::__construct($context);
    }
}
