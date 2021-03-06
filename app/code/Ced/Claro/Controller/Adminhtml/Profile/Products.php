<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_Claro
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license     http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Claro\Controller\Adminhtml\Profile;

use Magento\Framework\View\Result\PageFactory;

/**
 * TODO: check usability
 * Class Products
 * @package Ced\Claro\Controller\Adminhtml\Profile
 */
class Products extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;

    /** @var \Ced\Claro\Model\Profile */
    public $profile;

    /** @var \Magento\Framework\Registry  */
    public $coreRegistry;

    /**
     * Products constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Ced\Claro\Model\Profile $profile
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Ced\Claro\Model\Profile $profile
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
        $this->profile = $profile;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var string $id */
        $id = $this->getRequest()->getParam('id');
        if (isset($id) && !empty($id)) {
            $this->profile->load($id);
        }

        $this->coreRegistry->register('claro_profile', $this->profile);

        return $this->resultPageFactory->create();
    }
}
