<?php
    
namespace ITM\MagB1\Block\Adminhtml\Productfiles\Edit;
    
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('itm_magb1_form');
        $this->setTitle(__('ITM Information'));
    }
    
    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getUrl('itm_magb1/productfiles/save'),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ]
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
