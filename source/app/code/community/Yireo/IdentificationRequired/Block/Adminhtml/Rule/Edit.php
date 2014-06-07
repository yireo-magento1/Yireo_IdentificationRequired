<?php
/**
 * Yireo IdentificationRequired for Magento
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright (C) 2014 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * Rule overview-block
 */
class Yireo_IdentificationRequired_Block_Adminhtml_Rule_Edit extends Yireo_FormApi_Block_Adminhtml_Container
{
    protected $form_xml = 'identificationrequired/rule/rule.xml';

    protected $form_name = 'ruleForm';

    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('identificationrequired/rule/edit.phtml');

        $rule = Mage::getModel('identificationrequired/rule')->load($this->getRequest()->getParam('id', 0));
        $this->setRule($rule);
        $this->setFormData($rule->getData());
    }

    public function getProducts()
    {
    }

    public function getCategories()
    {
    }
}
