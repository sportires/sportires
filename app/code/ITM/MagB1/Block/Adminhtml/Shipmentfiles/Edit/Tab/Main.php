<?php
    
namespace ITM\MagB1\Block\Adminhtml\Shipmentfiles\Edit\Tab;
    
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use ITM\MagB1\Model\System\Config\Status;
    
class Main extends Generic implements TabInterface
{

    protected $_status;
    
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        Status $status,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_status = $status;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    
   /**
    *
    * {@inheritdoc}
    */
    public function getTabLabel()
    {
        return __('Item Information');
    }
                
   /**
    *
    * {@inheritdoc}
    */
    public function getTabTitle()
    {
        return __('Item Information');
    }
                
   /**
    *
    * {@inheritdoc}
    */
    public function canShowTab()
    {
        return true;
    }
                
   /**
    *
    * {@inheritdoc}
    */
    public function isHidden()
    {
        return false;
    }
                
    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_itm_magb1_shipmentfiles');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('Item Information')
        ]);
        if ($model->getEntityId()) {
            $fieldset->addField('entity_id', 'hidden', [
              'name' => 'id'
             ]);
        }

        if ($model->getData("increment_id")!="") {
            $fieldset->addField('increment_id', 'text', [
                'name' => 'increment_id',
                'required' => true,
                'readonly' => true,
                'label' => __('Increment ID'),
                'title' => __('Increment ID'),
                ]);
        } else {
             $fieldset->addField('increment_id', 'text', [
                 'name' => 'increment_id',
                 'required' => true,
                 'label' => __('Increment ID'),
                 'title' => __('Increment ID'),
             ]);
        }
       
        $file_name = "";
        $after_element = "";
        if ($model->getData("path")!="") {
            $file_name = $model->getData("path");
            $after_element = "<p><input type=\"checkbox\" name=\"delete_file_path\">Delete ($file_name)</p>";
        }
        
        $fieldset->addField('path', 'file', [
            'name' => 'path',
            'required' => false,
            'label' => __('Select file to upload'),
            'title' => __('Select file to upload'),
            'after_element_html' => $after_element,
            //'before_element_html' => '',
        ]);
        
        $fieldset->addField('description', 'text', [
            'name' => 'description',
            'required' => false,
            'label' => __('Description'),
            'title' => __('Description'),
            ]);

        $fieldset->addField('store_id', 'select', [
            'name' => 'store_id',
            'required' => true,
            'label' => __('Store View'),
            'title' => __('Store View'),
            'values' => $this->_systemStore->getStoreValuesForForm(false, true)
        ]);

        $fieldset->addField('position', 'text', [
            'name' => 'position',
            'required' => false,
            'label' => __('Position'),
            'title' => __('Position'),
            ]);
                
        $fieldset->addField('status', 'select', [
            'name' => 'status',
            'label' => __('Status'),
            'options' => $this->_status->toOptionArray()
        ]);
                
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
