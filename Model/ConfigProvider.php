<?php

declare(strict_types=1);

namespace OH\Compare\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ConfigProvider
 * @package OH\Compare\Model
 */
class ConfigProvider
{
    /**
     * @var string
     */
    const XML_CONFIG_PATH_RESTRICT_QTY = 'oh_compare/settings/restrict_qty';

    /**
     * @var string
     */
    const XML_CONFIG_PATH_QTY_ALLOWED = 'oh_compare/settings/qty_allowed';

    /**
     * @var ScopeInterface
     */
    private $scopeInterface;

    public function __construct(
        ScopeConfigInterface $scopeInterface
    ) {
        $this->scopeInterface = $scopeInterface;
    }

    /**
     * Check if qty list is restricted
     *
     * @return bool
     */
    public function restrictQty(): ?bool
    {
        return $this->scopeInterface->isSetFlag(self::XML_CONFIG_PATH_RESTRICT_QTY, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get allowed qty
     *
     * @return int
     */
    public function getAllowedQty(): ?int
    {
        return (int)$this->scopeInterface->getValue(
            self::XML_CONFIG_PATH_QTY_ALLOWED,
            ScopeInterface::SCOPE_STORE);
    }
}
