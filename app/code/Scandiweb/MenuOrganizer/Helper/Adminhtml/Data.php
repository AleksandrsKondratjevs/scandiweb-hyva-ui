<?php

/**
 * @category  Scandiweb
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

namespace Scandiweb\MenuOrganizer\Helper\Adminhtml;

use Magento\Backend\App\ConfigInterface;
use Magento\Captcha\Model\CaptchaFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManager;
use ScandiPWA\MenuOrganizer\Helper\Adminhtml\Data as SourceData;

/**
 * Class Data
 * This class is preferenced over the original class in the ScandiPWA_MenuOrganizer module
 * Reason: To add a new url type to the Item model
 */
class Data extends SourceData
{
    /**
     * Item's URL types
     */
    const TYPE_CUSTOM_URL = 0;
    const TYPE_CMS_PAGE = 1;
    const TYPE_CATEGORY = 2;
    const TYPE_PRODUCT = 3;

    /**
     * @var ConfigInterface
     */
    protected $_backendConfig;

    protected $_categoryCollection;
    protected $_categoryCollectionClass = \Magento\Catalog\Model\ResourceModel\Category\Collection::class;

    protected $_menuCollection;
    protected $_menuCollectionClass = \ScandiPWA\MenuOrganizer\Model\ResourceModel\Menu\Collection::class;

    protected $_pageCollection;
    protected $_pageCollectionClass = \Magento\Cms\Model\ResourceModel\Page\Collection::class;

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @param Context $context
     * @param StoreManager $storeManager
     * @param Filesystem $filesystem
     * @param CaptchaFactory $factory
     * @param ConfigInterface $backendConfig
     * @param Registry $_coreRegistry
     */
    public function __construct(
        Context $context,
        StoreManager $storeManager,
        Filesystem $filesystem,
        CaptchaFactory $factory,
        ConfigInterface $backendConfig,
        Registry $_coreRegistry
    ) {
        parent::__construct($context, $storeManager, $filesystem, $factory, $backendConfig, $_coreRegistry);
    }

    /**
     * Prepare available item url types
     *
     * @return array
     */
    public function getUrlTypes()
    {
        return [
            self::TYPE_CUSTOM_URL => __('Custom URL'),
            self::TYPE_CMS_PAGE => __('CMS Page'),
            self::TYPE_CATEGORY => __('Category'),
            self::TYPE_PRODUCT => __('Product')
        ];
    }
}
