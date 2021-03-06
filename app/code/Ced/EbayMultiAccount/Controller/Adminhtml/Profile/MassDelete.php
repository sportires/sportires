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
 * @package     Ced_EbayMultiAccount
 * @author        CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\EbayMultiAccount\Controller\Adminhtml\Profile;

/**
 * Class MassDelete
 * @package Ced\EbayMultiAccount\Controller\Adminhtml\Profile
 */
class MassDelete extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Ced_EbayMultiAccount::EbayMultiAccount';

    public function execute()
    {
        $profileIds = $this->getRequest()->getParam('selected');
        $excluded = $this->getRequest()->getParam('excluded', false);
        if (!is_array($profileIds) && !$excluded) {
            $this->messageManager->addErrorMessage(__('Please select Profile(s).'));
        } else if ($excluded == "false") {
            $profileIds = $this->_objectManager->create('Ced\EbayMultiAccount\Model\Profile')->getCollection()->getAllIds();
        }

        if (!empty($profileIds)) {
            try {
                foreach ($profileIds as $profileId) {
                    $profile = $this->_objectManager->create('Ced\EbayMultiAccount\Model\Profile')->load($profileId);
                    $profile->delete();
                }
                $this->messageManager->addSuccessMessage(__('Total of %1 record(s) have been deleted.', count($profileIds)));
            } catch (\Exception $e) {
                $this->_objectManager->create('Ced\EbayMultiAccount\Helper\Logger')->addError('In Mass Delete Profile: '.$e->getMessage(), ['path' => __METHOD__]);
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}
