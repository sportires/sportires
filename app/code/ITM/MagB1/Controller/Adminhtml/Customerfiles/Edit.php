<?php
    
namespace ITM\MagB1\Controller\Adminhtml\Customerfiles;
    
class Edit extends \ITM\MagB1\Controller\Adminhtml\Customerfiles
{
    
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('ITM\MagB1\Model\Customerfiles');
        if ($id) {
            $model->load($id);
            if (!$model->getEntityId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('itm_magb1/*');
                return;
            }
        }
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $resultPage = $this->resultPageFactory->create();
        if ($id) {
            $resultPage->getConfig()->getTitle()->prepend(__('Edit Items Entry'));
        } else {
            $resultPage->getConfig()->getTitle()->prepend(__('Add Items Entry'));
        }
    
        $this->_coreRegistry->register('current_itm_magb1_customerfiles', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('customerfiles_customerfiles_edit');
        $this->_view->renderLayout();
    }
}
