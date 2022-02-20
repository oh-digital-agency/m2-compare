<?php
declare(strict_types=1);

namespace OH\Compare\Plugin;

use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use OH\Compare\Model\ConfigProvider;

/**
 * Class AddPlugin
 * @package OH\Compare\Plugin
 */
class AddPlugin
{
    /**
     * @var \Magento\Catalog\Helper\Product\Compare
     */
    protected $compareHelper;

    /**
     * @var ManagerInterface
     */
    protected $messagaeManager;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * @var ConfigProvider
     */
    protected $configProvider;

    public function __construct(
        ConfigProvider $configProvider,
        UrlInterface $url,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        ManagerInterface $messagaeManager,
        \Magento\Catalog\Helper\Product\Compare $compareHelper
    ) {
        $this->url = $url;
        $this->configProvider = $configProvider;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messagaeManager = $messagaeManager;
        $this->compareHelper = $compareHelper;
    }

    public function aroundExecute(\Magento\Catalog\Controller\Product\Compare\Add $add, \Closure $proceed)
    {
        $collection = $this->compareHelper->getItemCollection();
        $maxAllowed = $this->configProvider->getAllowedQty();

        if (!$this->configProvider->restrictQty() || $collection->count() < $maxAllowed) {
            return $proceed();
        }

        $this->messagaeManager->addWarningMessage(__('You can not add more than %1 items.', $maxAllowed));
        return $this->resultRedirectFactory->create()->setUrl($this->url->getUrl('catalog/product_compare'));
    }
}
