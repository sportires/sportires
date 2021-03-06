<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Claro
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Claro\Controller\Adminhtml\Product;

/**
 * Class Update
 *
 * @package Ced\Claro\Controller\Adminhtml\Product
 */
class Update extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * @var \Ced\Claro\Helper\Product
     */
    public $product;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    public $productCollectionFactory;

    /** @var \Magento\Backend\Model\Session  */
    public $session;

    /** @var \Magento\Framework\Registry  */
    public $registry;

    /** @var \Magento\Framework\Controller\Result\JsonFactory  */
    public $resultJsonFactory;

    /** @var \Magento\Framework\View\Result\PageFactory  */
    public $resultPageFactory;

    /** @var \Ced\Claro\Helper\Config  */
    public $config;

    /**
     * Update constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collection
     * @param \Ced\Claro\Helper\Config $config
     * @param \Ced\Claro\Helper\Product $product
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collection,
        \Ced\Claro\Helper\Config $config,
        \Ced\Claro\Helper\Product $product
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->productCollectionFactory = $collection;
        $this->config = $config;

        $this->product = $product;
        $this->session =  $context->getSession();
        $this->registry = $registry;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        // case 1 check if api config are valid
        if (!$this->config->isValid()) {
            $this->messageManager->addErrorMessage(
                __('Products Upload Failed. Claro API not enabled or Invalid. Please check Claro Configuration.')
            );
            $resultRedirect = $this->resultFactory->create('redirect');
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }

        // case 2.1 normal uploading and chunk creating
        $isFilter = $this->getRequest()->getParam('filters');
        $ids = [];
        if (isset($isFilter)) {
            $collection = $this->filter->getCollection($this->productCollectionFactory->create());
            $ids = $collection->getAllIds();
        }

        if (empty($ids)) {
            $this->messageManager->addErrorMessage('No Product(s) selected to upload.');
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create('redirect');
            $resultRedirect->setPath('*/*/index');
            return $resultRedirect;
        }

        $chunkSize = $this->config->getChunkSize(\Ced\Claro\Helper\Config::ACTION_PRODUCT_UPDATE);
        // case 2.2 normal uploading if current ids are less than chunk size.
        if (count($ids) <= $chunkSize) {
            $response = $this->product->update($ids);
            if ($response) {
                $this->messageManager->addSuccessMessage(
                    count($ids) . ' Product(s) update request submitted successfully.'
                );
            } else {
                $message = 'Product(s) update failed.';
                if (isset($errors)) {
                    $message = "Product(s) update failed. \nErrors: " . (string)json_encode($errors);
                }
                $this->messageManager->addError($message);
            }

            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create('redirect');
            $resultRedirect->setPath('*/*/index');
            return $resultRedirect;
        }

        // case 3.2 normal uploading if current ids are more than chunk size.
        $key = uniqid(\Ced\Claro\Helper\Config::ACTION_PRODUCT_UPDATE.'_');
        $ids = array_chunk($ids, $chunkSize);
        $index = 'set'.$key;
        $this->session->$index($ids);
        $this->registry->register($key, count($ids));
        $this->registry->register(\Ced\Claro\Helper\Config::PRODUCT_ACTION_KEY, $key);
        $this->registry->register(
            \Ced\Claro\Helper\Config::PRODUCT_ACTION_TYPE,
            \Ced\Claro\Helper\Config::ACTION_PRODUCT_UPDATE
        );
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}
